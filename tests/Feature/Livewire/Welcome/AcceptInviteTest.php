<?php

use App\Livewire\Welcome\AcceptInvite;
use App\Models\Invite;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    // Create test user and organization
    $this->user = User::factory()->create(['first_name' => '', 'last_name' => '']);
    [$user, $organization] = createOrganization($this->user);
    $this->organization = $organization;

    // Create an invite for the user
    $this->inviteeUser = User::factory()->create(['first_name' => '', 'last_name' => '']);
    $this->invite = Invite::factory()->for($this->organization)->for($this->user, 'inviter')->withRole($this->organization)->create([
        'email' => $this->inviteeUser->email,
    ]);
    actingAs($this->inviteeUser);

});

it('renders the accept invite component', function () {

    Livewire::test(AcceptInvite::class, ['invite' => $this->invite])
        ->assertViewIs('livewire.welcome.accept-invite')
        ->assertSee($this->organization->name)
        ->assertSee($this->invite->inviter->fullName);
});

it('shows the form if user first name is empty', function () {
    Livewire::test(AcceptInvite::class, ['invite' => $this->invite])
        ->assertSet('showForm', true);
});

it('does not show the form if user first name is not empty', function () {
    $this->inviteeUser->update(['first_name' => 'John']);
    Livewire::test(AcceptInvite::class, ['invite' => $this->invite])
        ->assertSet('showForm', false);
});

it('validates the form fields when accepting the invite', function () {
    Livewire::test(AcceptInvite::class, ['invite' => $this->invite])
        ->set('first_name', '')
        ->call('accept')
        ->assertHasErrors(['first_name' => 'required']);
});

it('accepts the invite and redirects to the dashboard', function () {
    Livewire::test(AcceptInvite::class, ['invite' => $this->invite])
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->call('accept')
        ->assertRedirect(route('dashboard', ['organization' => $this->organization->slug]));

    $this->assertDatabaseMissing('invites', ['id' => $this->invite->id]);
});

it('validates role id belongs to the organization', function () {
    $this->invite->update(['role_id' => 1]);

    Livewire::test(AcceptInvite::class, ['invite' => $this->invite])
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->call('accept');

    $this->assertDatabaseHas('invites', ['id' => $this->invite->id]);
});

it('deletes the invite if user already belongs to the organization', function () {
    $this->inviteeUser->organizations()->attach($this->organization->id, ['role_id' => $this->organization->roles()->first()->id]);

    Livewire::test(AcceptInvite::class, ['invite' => $this->invite])
        ->assertRedirect(route('dashboard', ['organization' => $this->organization->slug]));

    $this->assertDatabaseMissing('invites', ['id' => $this->invite->id]);
});

it('sets inviteBelongstoAuthenticatedUser to true if invite email matches authenticated user email', function () {
    // Ensure the invite email matches the authenticated user's email
    $this->invite->update(['email' => $this->inviteeUser->email]);

    Livewire::test(AcceptInvite::class, ['invite' => $this->invite])
        ->assertSet('inviteBelongstoAuthenticatedUser', true);
});

it('sets inviteBelongstoAuthenticatedUser to false if invite email does not match authenticated user email', function () {
    // Ensure the invite email does not match the authenticated user's email
    $this->invite->update(['email' => 'different@example.com']);

    Livewire::test(AcceptInvite::class, ['invite' => $this->invite])
        ->assertSet('inviteBelongstoAuthenticatedUser', false)
        ->assertDontSee('invited you to join') // Replace with actual text in your view
        ->assertSee('Sorry, this invite does not belong to you.') // Replace with the actual fallback text
        ->assertSee('Please make sure you log in with the email you received the invite on.') // Replace with the actual fallback text
        ->assertDontSee($this->organization->name); // Ensure organization name is not shown if invalid
});
