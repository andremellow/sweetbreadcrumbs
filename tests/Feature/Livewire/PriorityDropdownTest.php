<?php

use App\Livewire\PriorityDropdown;
use App\Services\OrganizationService;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;
    URL::defaults(['organization' => $this->organization->slug]);

    Context::add('current_organization', $this->organization);
});

afterEach(function () {
    Mockery::close();
});

it('renders successfully', function () {
    // Mock the OrganizationService

    Livewire::actingAs($this->user)
        ->test(PriorityDropdown::class)
        // ->set('priorityId', 2) // Ensure it binds the model correctly
        ->assertSee('Highest')
        ->assertSee('High')
        ->assertSee('Midium')
        ->assertSee('Lowest');
});
