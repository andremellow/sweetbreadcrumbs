<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Livewire\PriorityDropdown;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
});

afterEach(function () {
    Mockery::close();
});

it('renders successfully', function () {
    // Mock the OrganizationService
    $organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization'));

    URL::defaults(['organization' => $organization->slug]);

    Livewire::actingAs($this->user)
        ->test(PriorityDropdown::class, ['organization' => $organization])
        // ->set('priorityId', 2) // Ensure it binds the model correctly
        ->assertSee('Highest')
        ->assertSee('High')
        ->assertSee('Midium')
        ->assertSee('Lowest');
});
