<?php

use App\Livewire\RoleDropdown;
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
        ->test(RoleDropdown::class)
        ->assertSee('Admin')
        ->assertSee('Contributor')
        ->assertSee('Viewer');
});
