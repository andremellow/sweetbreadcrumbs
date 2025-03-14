<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Livewire\PriorityDropdown;
use App\Models\User;
use App\Services\OrganizationService;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization'));
    URL::defaults(['organization' => $this->organization->slug]);

    app()->bind(OrganizationService::class, function () {
        return new OrganizationService(
            app(CreateOrganization::class),
            $this->organization
        );
    });
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
