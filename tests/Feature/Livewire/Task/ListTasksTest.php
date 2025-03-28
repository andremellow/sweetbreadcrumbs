<?php

use App\Enums\EventEnum;
use App\Enums\SortDirection;
use App\Livewire\Task\ListTasks;
use App\Models\Task;
use App\Models\User;
use App\Models\Workstream;
use Carbon\Carbon;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;

    // Create test workstreams
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();
    $this->tasks = Task::factory(3)->for($this->workstream, 'taskable')->withPriority($this->organization)->create();

    URL::defaults(['organization' => $this->organization->slug]);
    View::share('currentOrganizationSlug', $this->organization->slug);

    Context::add('current_organization', $this->organization);
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

it('listerning for task-deleted', function () {
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

it('listerning for task-created', function () {
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

it('does not reload when status is all', function () {
    try {
        Livewire::actingAs($this->user)
            ->test(ListTasks::class, ['workstream' => $this->workstream, 'organization' => $this->organization])
            ->set('status', 'all')
            ->assertViewHas('tasks', function ($tasks) {
                return count($tasks) === 3;
            })
            ->dispatch(EventEnum::TASK_CLOSED->value)
            ->assertViewHas('tasks', function ($tasks) {
                return count($tasks) === 3;
            });
        $this->fail('exception not thrown');
    } catch (\Throwable $th) {
        expect($th->getMessage())->toBe('The response is not a view.');
    }
});

it('reloads when status is not all', function () {
    Livewire::actingAs($this->user)
        ->test(ListTasks::class, ['workstream' => $this->workstream, 'organization' => $this->organization])
        ->set('status', 'open')
        ->assertViewHas('tasks', function ($tasks) {
            return count($tasks) === 3;
        })
        ->tap(function () {
            $this->tasks[0]->update(['completed_at' => Carbon::now()]);
        })
        ->dispatch(EventEnum::TASK_CLOSED->value)
        ->assertViewHas('tasks', function ($tasks) {
            return count($tasks) === 2;
        });
});
