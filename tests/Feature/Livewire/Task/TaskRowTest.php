<?php

use App\Enums\EventEnum;
use App\Livewire\Task\TaskRow;
use App\Models\Task;
use App\Models\User;
use App\Models\Workstream;
use App\Services\TaskService;
use Carbon\Carbon;
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
    $this->task = Task::factory()->for($this->workstream, 'taskable')->withPriority($this->organization)->create();

    URL::defaults(['organization' => $this->organization->slug]);
    View::share('currentOrganizationSlug', $this->organization->slug);
});

afterEach(function () {
    Mockery::close();
});

it('renders a late task', function () {
    $this->task->update(['due_date' => Carbon::now()->addDay(-1)]);
    $this->task->refresh();

    Livewire::actingAs($this->user)
        ->test(TaskRow::class, ['task' => $this->task])
        ->assertSee($this->task->name)
        ->assertSee($this->task->priority->name)
        ->assertSee($this->task->due_date->format('m/d/Y'))
        ->assertSeeHtml('wire:click="close')
        ->assertSeeHtml('is-late text-red-300')
        ->assertStatus(200);
});

it('refreshes task', function () {
    $date = Carbon::now()->addDay(9);
    $this->task->refresh();

    Livewire::actingAs($this->user)
        ->test(TaskRow::class, ['task' => $this->task])
        ->assertSee($this->task->name)
        ->assertSee($this->task->priority->name)
        ->assertSee($this->task->due_date->format('m/d/Y'))
        ->tap(function () use ($date) {
            $this->task->update([
                'name' => 'new name',
                'description' => '',
                'priority_id' => 6,
                'due_date' => $date,
            ]);
        })
        ->dispatch(EventEnum::TASK_UPDATED->value.".{$this->task->id}")
        ->assertSee('new name')
        ->assertSee($date->format('m/d/Y'))
        ->assertStatus(200);
});

it('renders a open task', function () {
    Livewire::actingAs($this->user)
        ->test(TaskRow::class, ['task' => $this->task])
        ->assertSee($this->task->name)
        ->assertSee($this->task->priority->name)
        ->assertSee($this->task->due_date->format('m/d/Y'))
        ->assertSeeHtml('wire:click="close')
        ->assertStatus(200);
});

it('renders a closed task', function () {
    $this->task->update(['completed_at' => Carbon::now()->addDay(-1)]);
    $this->task->refresh();

    Livewire::actingAs($this->user)
        ->test(TaskRow::class, ['task' => $this->task])
        ->assertSee($this->task->name)
        ->assertSee($this->task->priority->name)
        ->assertSee($this->task->due_date->format('m/d/Y'))
        ->assertSeeHtml('wire:click="open')
        ->assertSeeHtml('is-completed line-through')
        ->assertStatus(200);
});

it('opens a task successfully and dispatches event', function () {
    $this->task->update(['completed_at' => Carbon::now()]);
    $this->task->refresh();
    expect($this->task->completed_at->toDateString())->toBe(Carbon::now()->toDateString());

    Livewire::actingAs($this->user)
        ->test(TaskRow::class, ['task' => $this->task])
        ->call('open', app(TaskService::class), $this->task->id)
        ->assertDispatched(EventEnum::TASK_OPENED->value, taskId: $this->task->id);

    $this->task->refresh();

    expect($this->task->completed_at)->toBeNull();
});

it('closes a task successfully and dispatches event', function () {
    Livewire::actingAs($this->user)
        ->test(TaskRow::class, ['task' => $this->task])
        ->call('close', app(TaskService::class), $this->task->id)
        ->assertDispatched(EventEnum::TASK_CLOSED->value, taskId: $this->task->id);

    $this->task->refresh();

    expect($this->task->completed_at->toDateString())->toBe(Carbon::now()->toDateString());
});

it('deletes a task successfully and dispatches event', function () {
    Livewire::actingAs($this->user)
        ->test(TaskRow::class, ['task' => $this->task])
        ->call('delete', app(TaskService::class), $this->task->id)
        ->assertDispatched(EventEnum::TASK_DELETED->value, taskId: $this->task->id)
        ->assertDispatched(EventEnum::TASK_DELETED->value.'.'.$this->task->id);

    $this->task->refresh();

    expect($this->task->deleted_at)->not->toBeNull();
});
