<?php

use App\Models\Invite;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Dusk\Browser;

beforeEach(function () {
    [$user] = createOrganization();
    $this->user = $user;

    [$inviterUser, $inviterOrganization] = createOrganization(user: User::factory()->create());
    $this->inviterUser = $inviterUser;
    $this->inviterOrganization = $inviterOrganization;

});

test('it loads the page with no pending invites', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->loginAs($this->user)
            ->visit(route('settings.invites'))
            ->assertSee('No pending invites');
    });
});

test('it handles expired invites', function () {
    $this->invite = Invite::factory()->for($this->inviterOrganization)->for($this->inviterUser, 'inviter')->withRole($this->inviterOrganization)->create([
        'email' => $this->user->email,
        'sent_at' => Carbon::now()->subDays(8),
    ]);

    $this->browse(function (Browser $browser) {
        $browser
            ->loginAs($this->user)
            ->visit(route('settings.invites'))
            ->assertSee($this->invite->organization->name)
            ->assertSee('Expired')
            ->assertSee('Decline')
            ->assertDontSee('Accept');
    });
});

test('it declies an invite', function () {
    $this->invite = Invite::factory()->for($this->inviterOrganization)->for($this->inviterUser, 'inviter')->withRole($this->inviterOrganization)->create([
        'email' => $this->user->email,
        'sent_at' => Carbon::now()->subDays(8),
    ]);

    $this->browse(function (Browser $browser) {
        $browser
            ->loginAs($this->user)
            ->visit(route('settings.invites'))
            ->assertSee($this->invite->organization->name)
            ->assertSee('Expired')
            ->press('Decline')
            ->acceptDialog()
            ->waitUntilMissingText($this->invite->organization->name)
            ->assertSee('No pending invites')
            ->waitForText('Invite declined');

        $this->assertDatabaseMissing('invites', [
            'id' => $this->invite->id,
        ]);

        $this->assertDatabaseMissing('organization_user', [
            'user_id' => $this->user->id,
            'organization_id' => $this->inviterOrganization->id,
        ]);
    });
});

test('it accepts an invite', function () {
    $this->invite = Invite::factory()->for($this->inviterOrganization)->for($this->inviterUser, 'inviter')->withRole($this->inviterOrganization)->create([
        'email' => $this->user->email,
    ]);

    $this->browse(function (Browser $browser) {
        $browser
            ->loginAs($this->user)
            ->visit(route('settings.invites'))
            ->assertSee($this->invite->organization->name)
            ->press('Accept')
            ->waitUntilMissingText($this->invite->organization->name)
            ->assertSee('No pending invites')
            ->waitForText('Invite accept');

        $this->assertDatabaseMissing('invites', [
            'id' => $this->invite->id,
        ]);

        $this->assertDatabaseHas('organization_user', [
            'user_id' => $this->user->id,
            'organization_id' => $this->inviterOrganization->id,
        ]);
    });
});

test('cannot accept invite if already part of the organization', function () {
    $this->invite = Invite::factory()->for($this->inviterOrganization)->for($this->inviterUser, 'inviter')->withRole($this->inviterOrganization)->create([
        'email' => $this->user->email,
    ]);

    $this->inviterOrganization->users()->attach($this->user->id, [
        'role_id' => $this->inviterOrganization->roles()->first()->id,
    ]);

    $this->browse(function (Browser $browser) {
        $browser
            ->loginAs($this->user)
            ->visit(route('settings.invites'))
            ->assertSee($this->invite->organization->name)
            ->press('Accept')
            ->assertSee($this->invite->organization->name);
        // ->assertSee(__(config('app.error_message')));
    });
});
