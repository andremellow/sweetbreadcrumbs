<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Enums\SortDirection;
use App\Livewire\Project\ListProjects;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use App\Services\OrganizationService;
use App\Services\ProjectService;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('new organization'));

    // Create test projects
    $this->projects = Project::factory(3)->for($this->organization)->withPriority($this->organization)->create();

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

it('renders the ListProjects component successfully', function () {
    Livewire::actingAs($this->user)
        ->test(ListProjects::class, ['organization' => $this->organization])
        ->assertStatus(200);
});

it('lists projects correctly', function () {
    Livewire::actingAs($this->user)
        ->test(ListProjects::class, ['organization' => $this->organization])
        ->assertSee($this->projects[0]->name)
        ->assertSee($this->projects[1]->name)
        ->assertSee($this->projects[2]->name);
});

it('filters projects by name', function () {
    Livewire::actingAs($this->user)
        ->test(ListProjects::class, ['organization' => $this->organization])
        ->set('name', $this->projects[1]->name)
        ->assertSee($this->projects[1]->name)
        ->assertDontSee($this->projects[0]->name)
        ->assertDontSee($this->projects[2]->name);
});

it('resets the filter values when resetForm is called', function () {
    Livewire::actingAs($this->user)
        ->test(ListProjects::class, ['organization' => $this->organization])
        ->set('name', 'Filtered Project')
        ->set('priorityId', 1)
        ->call('resetForm')
        ->assertSet('name', null)
        ->assertSet('priorityId', null);
});

it('sets sort column and direction', function () {
    // TODO: Maybe move this to a diffent method and test only the WithSort Trait
    Livewire::actingAs($this->user)
        ->test(ListProjects::class, ['organization' => $this->organization])
        ->set('sortBy', 'name') // Set sortBy Column
        ->assertSet('sortBy', 'name') // No secret, it should sorted
        ->call('sort', 'date') // Call sort method
        ->assertSet('sortBy', 'date') // It should set sortBy
        ->assertSet('sortDirection', SortDirection::ASC) // And because it is a new column, set it to ASK
        ->call('sort', 'date') // Sort the same column again
        ->assertSet('sortBy', 'date') // sortBy should be the same
        ->assertSet('sortDirection', SortDirection::DESC); // Should set it to DESC
});

it('deletes a project successfully and dispatches event', function () {
    $projectToDelete = $this->projects->first();

    Livewire::actingAs($this->user)
        ->test(ListProjects::class, ['organization' => $this->organization])
        ->call('delete', app(ProjectService::class), $projectToDelete->id)
        ->assertDispatched('project-deleted', projectId: $projectToDelete->id);

    $projectToDelete->refresh();

    expect($projectToDelete->deleted_at)->not->toBeNull();
});

it('applyFilter reload with right data', function () {
    Livewire::actingAs($this->user)
        ->test(ListProjects::class, ['organization' => $this->organization])
        ->set('name', $this->projects[1]->name)
        ->call('applyFilter')
        ->assertSee($this->projects[1]->name)
        ->assertDontSee($this->projects[0]->name)
        ->assertDontSee($this->projects[2]->name);
});
