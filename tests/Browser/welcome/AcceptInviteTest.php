<?php

use App\Models\Invite;
use App\Models\User;
use Laravel\Dusk\Browser;

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;

});

test('it validates the fields', function () {
    $this->inviteeUser = User::factory()->create(['first_name' => '', 'last_name' => '']);
    $this->invite = Invite::factory()->for($this->organization)->for($this->user, 'inviter')->withRole($this->organization)->create(
        ['email' => $this->inviteeUser->email]
    );

    $this->browse(function (Browser $browser) {
        $browser
            ->loginAs($this->inviteeUser)
            ->visit(route('invite.accept', ['invite' => $this->invite->token]))
            ->type('last_name', 'Mello')
            ->press('Accept')
            ->waitForText('The first name field is required.');

    });
});

test('it updates user name and accepts the invite', function () {
    $this->inviteeUser = User::factory()->create(['first_name' => '', 'last_name' => '']);
    $this->invite = Invite::factory()->for($this->organization)->for($this->user, 'inviter')->withRole($this->organization)->create(
        ['email' => $this->inviteeUser->email]
    );

    $this->browse(function (Browser $browser) {
        $browser
            ->loginAs($this->inviteeUser)
            ->visit(route('invite.accept', ['invite' => $this->invite->token]))
            ->type('first_name', 'John')
            ->type('last_name', 'Doe')
            ->press('Accept')
            ->waitForRoute('dashboard', ['organization' => $this->organization->slug]);
    });

    $this->inviteeUser->refresh();

    expect($this->inviteeUser->first_name)->toBe('John');
    expect($this->inviteeUser->last_name)->toBe('Doe');
    expect($this->inviteeUser->organizations()->where('organizations.id', $this->organization->id)->exists())->toBe(true);
});

test('accepts the invite', function () {
    $this->inviteeUser = User::factory()->create();
    $this->invite = Invite::factory()->for($this->organization)->for($this->user, 'inviter')->withRole($this->organization)->create(
        ['email' => $this->inviteeUser->email]
    );
    expect($this->inviteeUser->organizations()->where('organizations.id', $this->organization->id)->exists())->toBe(false);
    $this->browse(function (Browser $browser) {
        $browser
            ->loginAs($this->inviteeUser)
            ->visit(route('invite.accept', ['invite' => $this->invite->token]))
            ->press('Accept')
            ->waitForRoute('dashboard', ['organization' => $this->organization->slug]);
    });

    $this->inviteeUser->refresh();

    expect($this->inviteeUser->organizations()->where('organizations.id', $this->organization->id)->exists())->toBe(true);
});

test('validate if role belongs to the organization', function () {
    $this->inviteeUser = User::factory()->create();
    $this->invite = Invite::factory()->for($this->organization)->for($this->user, 'inviter')->create([
        'email' => $this->inviteeUser->email,
        'role_id' => 1,
    ]);

    $this->browse(function (Browser $browser) {
        $browser
            ->loginAs($this->inviteeUser)
            ->visit(route('invite.accept', ['invite' => $this->invite->token]))
            ->press('Accept')
            ->waitForText(config('app.error_message'))
            ->screenshot('alert');
    });
});

test('it cannot access someone elses invite', function () {
    $this->inviteeUser = User::factory()->create(['first_name' => '', 'last_name' => '']);
    $this->invite = Invite::factory()->for($this->organization)->for($this->user, 'inviter')->withRole($this->organization)->create();

    $this->browse(function (Browser $browser) {
        $browser
            ->loginAs($this->inviteeUser)
            ->visit(route('invite.accept', ['invite' => $this->invite->token]))
            ->waitForText('Sorry, this invite does not belong to you.')
            ->waitForText('Please make sure you log in with the email you received the invite on.');
    });
});
