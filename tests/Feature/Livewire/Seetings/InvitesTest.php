<?php

use App\Livewire\Settings\Invites;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;

    URL::defaults(['organization' => $this->organization->slug]);
    View::share('currentOrganizationSlug', $this->organization->slug);
});

it('renders the Invites component successfully', function () {
    Livewire::actingAs($this->user)
        ->test(Invites::class)
        ->assertStatus(200)
        ->assertSee('No pending invites');
});

it('lists invites', function () {

    [$otherUser, $otherOrganization] = createOrganization();
    $this->invite = Invite::factory()->for($otherOrganization)->for($otherUser, 'inviter')->withRole($otherOrganization)->create(
        ['email' => $this->user->email]
    );
    [$otherUser1, $otherOrganization1] = createOrganization();
    $this->invite1 = Invite::factory()->for($otherOrganization1)->for($otherUser1, 'inviter')->withRole($otherOrganization1)->create(
        ['email' => $this->user->email]
    );

    Livewire::actingAs($this->user)
        ->test(Invites::class)
        ->assertSee($otherOrganization->name)
        ->assertSee($otherOrganization1->name);
});

it('accepts an invite', function () {

    [$otherUser, $otherOrganization] = createOrganization();
    $this->invite = Invite::factory()->for($otherOrganization)->for($otherUser, 'inviter')->withRole($otherOrganization)->create(
        ['email' => $this->user->email]
    );
    [$otherUser1, $otherOrganization1] = createOrganization();
    $this->invite1 = Invite::factory()->for($otherOrganization1)->for($otherUser1, 'inviter')->withRole($otherOrganization1)->create(
        ['email' => $this->user->email]
    );

    Livewire::actingAs($this->user)
        ->test(Invites::class)
        ->call('accept', $this->invite->id)
        ->assertViewHas('invites', function ($invites) {
            return count($invites) == 1 && $invites[0]->id == $this->invite1->id;
        });
});

it('cannot accept an expired invite', function () {

    [$otherUser, $otherOrganization] = createOrganization();
    $this->invite = Invite::factory()->for($otherOrganization)->for($otherUser, 'inviter')->withRole($otherOrganization)->create(
        ['email' => $this->user->email]
    );
    $this->invite->update(['sent_at' => now()->subDay(10)]);

    Livewire::actingAs($this->user)
        ->test(Invites::class)
        ->call('accept', $this->invite->id)
        ->assertViewHas('invites', function ($invites) {
            return count($invites) == 1;
        });

});

it('declines an invite', function () {

    [$otherUser, $otherOrganization] = createOrganization();
    $this->invite = Invite::factory()->for($otherOrganization)->for($otherUser, 'inviter')->withRole($otherOrganization)->create(
        ['email' => $this->user->email]
    );
    [$otherUser1, $otherOrganization1] = createOrganization();
    $this->invite1 = Invite::factory()->for($otherOrganization1)->for($otherUser1, 'inviter')->withRole($otherOrganization1)->create(
        ['email' => $this->user->email]
    );

    Livewire::actingAs($this->user)
        ->test(Invites::class)
        ->call('decline', $this->invite->id)
        ->assertViewHas('invites', function ($invites) {
            return count($invites) == 1 && $invites[0]->id == $this->invite1->id;
        });
});
