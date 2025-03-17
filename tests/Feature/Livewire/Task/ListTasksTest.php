<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Enums\EventEnum;
use App\Enums\SortDirection;
use App\Livewire\Task\ListTasks;
use App\Models\Task;
use App\Models\User;
use App\Models\Workstream;
use App\Services\OrganizationService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('new organization'));

    // Create test workstreams
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();
    $this->tasks = Task::factory(3)->for($this->workstream, 'taskable')->withPriority($this->organization)->create();

    URL::defaults(['organization' => $this->organization->slug]);
    View::share('currentOrganizationSlug', $this->organization->slug);

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

it('renders the ListTasks component successfully', function () {
    Livewire::actingAs($this->user)
        ->test(ListTasks::class, ['workstream' => $this->workstream, 'organization' => $this->organization])
        ->assertSeeHtml('wire:submit="applyFilter"')
        ->assertSeeHtml('wire:model="search"')
        ->assertSeeHtml('wire:model.live.self="dateRange"')
        ->assertSeeHtml('wire:model.live="status"')
        ->assertSeeHtml('wire:click="toggleLate()"')
        // ->assertSeeHtml('data-modal="meeting-form-modal"')
        ->assertStatus(200);
});

it('lists tasks correctly', function () {
    Livewire::actingAs($this->user)
        ->test(ListTasks::class, ['workstream' => $this->workstream, 'organization' => $this->organization])
        ->assertSee($this->tasks[0]->name)
        ->assertSee($this->tasks[1]->name)
        ->assertSee($this->tasks[2]->name);
});

it('filters tasks by name', function () {
    Livewire::actingAs($this->user)
        ->test(ListTasks::class, ['workstream' => $this->workstream, 'organization' => $this->organization])
        ->set('search', $this->tasks[1]->name)
        ->assertViewHas('tasks', function ($tasks) {
            return count($tasks) === 1 && $tasks[0]->name === $this->tasks[1]->name;
        });
});

it('resets the filter values when resetForm is called', function () {

    Livewire::actingAs($this->user)
        ->test(ListTasks::class, ['workstream' => $this->workstream])
        ->set('search', 'Filtered Workstream')
        ->set('status', 'open')
        ->set('dateRange', [])
        ->set('priorityId', 6)
        ->set('onlyLates', true)
        ->call('resetForm')
        ->assertSet('search', null)
        ->assertSet('dateRange', null)
        ->assertSet('status', 'open')
        ->assertSet('priorityId', null)
        ->assertSet('onlyLates', null);
});

it('resets the filter values when reset event dispatched', function () {
    $component = Livewire::actingAs($this->user)
        ->test(ListTasks::class, ['workstream' => $this->workstream, 'organization' => $this->organization])
        ->set('search', 'Filtered Workstream');

    $component
        ->dispatch(EventEnum::RESET->value)
        ->assertSet('search', null);
});

it('sets sort column and direction', function () {
    // TODO: Maybe move this to a diffent method and test only the WithSort Trait
    Livewire::actingAs($this->user)
        ->test(ListTasks::class, ['workstream' => $this->workstream, 'organization' => $this->organization])
        ->set('sortBy', 'name') // Set sortBy Column
        ->assertSet('sortBy', 'name') // No secret, it should sorted
        ->call('sort', 'date') // Call sort method
        ->assertSet('sortBy', 'date') // It should set sortBy
        ->assertSet('sortDirection', SortDirection::ASC) // And because it is a new column, set it to ASK
        ->call('sort', 'date') // Sort the same column again
        ->assertSet('sortBy', 'date') // sortBy should be the same
        ->assertSet('sortDirection', SortDirection::DESC); // Should set it to DESC
});

it('toggles isLate filter', function () {
    // TODO: Maybe move this to a diffent method and test only the WithSort Trait
    Livewire::actingAs($this->user)
        ->test(ListTasks::class, ['workstream' => $this->workstream, 'organization' => $this->organization])
        ->assertSet('onlyLates', false)
        ->call('toggleLate')
        ->assertSet('onlyLates', true)
        ->call('toggleLate')
        ->assertSet('onlyLates', false);
});

it('applyFilter reload with right data', function () {
    Livewire::actingAs($this->user)
        ->test(ListTasks::class, ['workstream' => $this->workstream, 'organization' => $this->organization])
        ->set('search', $this->tasks[1]->name)
        ->call('applyFilter')
        ->assertViewHas('tasks', function ($tasks) {
            return count($tasks) === 1 && $tasks[0]->name === $this->tasks[1]->name;
        });
});

it('it listerning for task-deleted', function () {
    Livewire::actingAs($this->user)
        ->test(ListTasks::class, ['workstream' => $this->workstream, 'organization' => $this->organization])
        ->assertViewHas('tasks', function ($tasks) {
            return count($tasks) === 3;
        })
        ->tap(function () {
            $this->tasks[1]->delete();
        })
        ->dispatch(EventEnum::TASK_DELETED->value)
        ->assertViewHas('tasks', function ($tasks) {
            return count($tasks) === 2;
        });

});

it('it listerning for task-created', function () {
    Livewire::actingAs($this->user)
        ->test(ListTasks::class, ['workstream' => $this->workstream, 'organization' => $this->organization])
        ->assertViewHas('tasks', function ($tasks) {
            return count($tasks) === 3;
        })
        ->tap(function () {
            Task::factory()->for($this->workstream, 'taskable')->withPriority($this->organization)->create();
        })
        ->dispatch(EventEnum::TASK_CREATED->value)
        ->assertViewHas('tasks', function ($tasks) {
            return count($tasks) === 4;
        });
});
