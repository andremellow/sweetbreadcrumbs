<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Enums\SortDirection;
use App\Livewire\Workstream\ListWorkstreams;
use App\Models\Workstream;
use App\Models\User;
use App\Services\OrganizationService;
use App\Services\WorkstreamService;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('new organization'));

    // Create test workstreams
    $this->workstreams = Workstream::factory(3)->for($this->organization)->withPriority($this->organization)->create();

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

it('renders the ListWorkstreams component successfully', function () {
    Livewire::actingAs($this->user)
        ->test(ListWorkstreams::class, ['organization' => $this->organization])
        ->assertStatus(200);
});

it('lists workstreams correctly', function () {
    Livewire::actingAs($this->user)
        ->test(ListWorkstreams::class, ['organization' => $this->organization])
        ->assertSee($this->workstreams[0]->name)
        ->assertSee($this->workstreams[1]->name)
        ->assertSee($this->workstreams[2]->name);
});

it('filters workstreams by name', function () {
    Livewire::actingAs($this->user)
        ->test(ListWorkstreams::class, ['organization' => $this->organization])
        ->set('name', $this->workstreams[1]->name)
        ->assertSee($this->workstreams[1]->name)
        ->assertDontSee($this->workstreams[0]->name)
        ->assertDontSee($this->workstreams[2]->name);
});

it('resets the filter values when resetForm is called', function () {
    Livewire::actingAs($this->user)
        ->test(ListWorkstreams::class, ['organization' => $this->organization])
        ->set('name', 'Filtered Workstream')
        ->set('priorityId', 1)
        ->call('resetForm')
        ->assertSet('name', null)
        ->assertSet('priorityId', null);
});

it('sets sort column and direction', function () {
    // TODO: Maybe move this to a diffent method and test only the WithSort Trait
    Livewire::actingAs($this->user)
        ->test(ListWorkstreams::class, ['organization' => $this->organization])
        ->set('sortBy', 'name') // Set sortBy Column
        ->assertSet('sortBy', 'name') // No secret, it should sorted
        ->call('sort', 'date') // Call sort method
        ->assertSet('sortBy', 'date') // It should set sortBy
        ->assertSet('sortDirection', SortDirection::ASC) // And because it is a new column, set it to ASK
        ->call('sort', 'date') // Sort the same column again
        ->assertSet('sortBy', 'date') // sortBy should be the same
        ->assertSet('sortDirection', SortDirection::DESC); // Should set it to DESC
});

it('deletes a workstream successfully and dispatches event', function () {
    $workstreamToDelete = $this->workstreams->first();

    Livewire::actingAs($this->user)
        ->test(ListWorkstreams::class, ['organization' => $this->organization])
        ->call('delete', app(WorkstreamService::class), $workstreamToDelete->id)
        ->assertDispatched('workstream-deleted', workstreamId: $workstreamToDelete->id);

    $workstreamToDelete->refresh();

    expect($workstreamToDelete->deleted_at)->not->toBeNull();
});

it('applyFilter reload with right data', function () {
    Livewire::actingAs($this->user)
        ->test(ListWorkstreams::class, ['organization' => $this->organization])
        ->set('name', $this->workstreams[1]->name)
        ->call('applyFilter')
        ->assertSee($this->workstreams[1]->name)
        ->assertDontSee($this->workstreams[0]->name)
        ->assertDontSee($this->workstreams[2]->name);
});
