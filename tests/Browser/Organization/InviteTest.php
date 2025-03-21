<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\Invite;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Dusk\Browser;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('SBC'));
    $this->invite = Invite::factory()->for($this->organization)->for($this->user, 'inviter')->withRole($this->organization)->create();
});

test('it loads the page', function () {
    $this->invite->delete();
    $this->browse(function (Browser $browser) {
        $browser
            ->loginAs($this->user)
            ->visit(route('organization.invite', [
                'organization' => $this->organization->slug,
            ]))
            ->assertSee('No pending invites')
            ->assertSee('Email')
            ->assertSee('Role')
            ->assertSee('Send');
    });
});

test('it validates email is required', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->loginAs($this->user)
            ->visit(route('organization.invite', [
                'organization' => $this->organization->slug,
            ]))
            ->press('Send')
            ->waitForText('The email field is required.');
    });
});

test('it validates email are unique', function () {

    $this->browse(function (Browser $browser) {
        $browser
            ->loginAs($this->user)
            ->visit(route('organization.invite', [
                'organization' => $this->organization->slug,
            ]))
            ->type('email', $this->invite->email)
            ->press('Send')
            ->waitForText("{$this->invite->email} was already invited");
    });
});

test('it sends an invate', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->loginAs($this->user)
            ->visit(route('organization.invite', [
                'organization' => $this->organization->slug,
            ]))
            ->type('email', 'johndoe@gmail.com')
            ->press('Send')
            ->waitForText('johndoe@gmail.com')
            ->waitForText('Invite sent')
            ->assertButtonDisabled('Resend');

    });
});

test('it resends the invite', function () {
    $this->invite->update(['sent_at' => Carbon::now()->addMinute(-6)]);

    $this->browse(function (Browser $browser) {
        $browser
            ->loginAs($this->user)
            ->visit(route('organization.invite', [
                'organization' => $this->organization->slug,
            ]))
            ->press('Resend')
            ->waitForText('Invite sent')
            ->assertButtonDisabled('Resend');
    });
});

test('it manually disable the resend button in try to resend the invite', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->loginAs($this->user)
            ->visit(route('organization.invite', [
                'organization' => $this->organization->slug,
            ]))
            ->script('document.querySelector("[dusk=resend]").disabled = false;');

        $browser->assertButtonEnabled('Resend')
            ->press('Resend')
            ->waitForText('Something went wrong')
            ->assertButtonDisabled('Resend');
    });
});

test('it deletes an invite', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->loginAs($this->user)
            ->visit(route('organization.invite', [
                'organization' => $this->organization->slug,
            ]))
            ->press('Cancel')
            ->assertDialogOpened('Are you sure you want to cancel this invite?')
            ->acceptDialog()
            ->waitForText('Invite canceled')
            ->assertSee('No pending invites');
    });
});
