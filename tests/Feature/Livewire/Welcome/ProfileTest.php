<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Livewire\Welcome\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Context;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    $this->user = User::factory()->create(['first_name' => '', 'last_name' => '']);
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
    $organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('new organization'));
    Context::add('current_organization', $organization);
    Livewire::actingAs($this->user)
        ->test(Profile::class)
        ->assertStatus(200)
        ->set('first_name', 'John')
        ->set('last_name', 'dDoe')
        ->call('update')
        ->assertRedirectToRoute('dashboard', ['organization' => $organization->slug]);
});
