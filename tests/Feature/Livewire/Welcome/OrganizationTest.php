<?php

use App\Livewire\Welcome\Organization;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    $this->user = User::factory()->create(['first_name' => '', 'last_name' => '']);
    [$user, $organization] = createOrganization($this->user);
    $this->organization = $organization;
});

it('validates name is required', function () {
    Livewire::actingAs($this->user)
        ->test(Organization::class)
        ->call('create')
        ->assertHasErrors([
            'name' => ['The name field is required.'],
        ])
        ->set('name', 'New Organization Name')
        ->call('create')
        ->assertHasErrors([
            'name' => ['The name has already been taken.'],
        ])
        ->set('name', 'another new organization')
        ->call('create')
        ->assertRedirectToRoute('welcome.workstream', ['organization' => 'another-new-organization']);
});
