<?php

use App\Livewire\Welcome\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Context;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    $this->user = User::factory()->create(['first_name' => '', 'last_name' => '']);
    [$user, $organization] = createOrganization();
    $this->organization = $organization;
});

it('renders the profile component successfully', function () {
    Livewire::actingAs($this->user)
        ->test(Profile::class)
        ->assertStatus(200)
        ->set('first_name', 'John')
        ->set('last_name', 'dDoe')
        ->call('update')
        ->assertRedirectToRoute('welcome.organization');
});

it('directs to dashboard if the user has an organization', function () {

    Context::add('current_organization', $this->organization);
    Livewire::actingAs($this->user)
        ->test(Profile::class)
        ->assertStatus(200)
        ->set('first_name', 'John')
        ->set('last_name', 'dDoe')
        ->call('update')
        ->assertRedirectToRoute('dashboard', ['organization' => $this->organization->slug]);
});
