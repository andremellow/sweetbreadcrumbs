<?php

use App\Livewire\Welcome\Organization;
use App\Livewire\Welcome\Workstream;
use App\Models\User;
use Illuminate\Support\Facades\Context;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    $this->user = User::factory()->create(['first_name' => '', 'last_name' => '']);
    [$user, $organization] = createOrganization($this->user);
    $this->organization = $organization;

    Context::add('current_organization', $this->organization);

});

it('validates name is required', function () {
    Livewire::actingAs($this->user)
        ->test(Workstream::class)
        ->call('create')
        ->assertHasErrors([
            'name' => ['The name field is required.'],
        ])
        ->set('name', 'n')
        ->call('create')
        ->assertHasErrors([
            'name' => ['The name field must be at least 2 characters.'],
        ])
        ->set('name', 'My workstream')
        ->call('create')
        ->assertRedirectToRoute('workstreams.dashboard', ['organization' => $this->organization->slug, 'workstream' => 1]);
});
