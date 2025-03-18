<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Enums\ConfigEnum;
use App\Livewire\Task\TaskModal;
use App\Models\Task;
use App\Models\User;
use App\Models\Workstream;
use App\Services\ConfigService;
use App\Services\OrganizationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('new organization'));
    app()->bind(OrganizationService::class, function () {
        return new OrganizationService(
            app(CreateOrganization::class),
            $this->organization
        );
    });

    $this->configService = app(ConfigService::class);

    // Create test workstreams
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();
    $this->task = Task::factory()->for($this->workstream, 'taskable')->withPriority($this->organization)->create();

    URL::defaults(['organization' => $this->organization->slug]);

});

afterEach(function () {
    Mockery::close();
});

it('renders the TaskModal component successfully', function () {
    Livewire::actingAs($this->user)
        ->test(TaskModal::class, ['workstream' => $this->workstream])
        ->assertStatus(200)
        ->assertSee('Small Steps, Big Wins—Let’s Tackle It!')
        ->assertSee($this->workstream->name)
        ->assertSee('Name')
        ->assertSee('Description')
        ->assertSee('Priority')
        ->assertSee('Due date')
        ->assertSeeHtml('wire:model="form.name"')
        ->assertSeeHtml('wire:model="form.description"')
        ->assertSeeHtml('wire:model.self="form.due_date"')
        ->assertSeeHtml('data-modal="task-form-modal"')
        ->assertSeeHtml('Save');
});

it('loads a task', function () {
    Livewire::actingAs($this->user)
        ->test(TaskModal::class, [
            'workstream' => $this->workstream,
        ])
        ->call('load', $this->task->id)
        ->assertSet('form.id', $this->task->id)
        ->assertSet('form.name', $this->task->name)
        ->assertSet('form.description', $this->task->description)
        ->assertSet('form.priority_id', $this->task->priority_id)
        ->assertSet('form.due_date', function ($due_date) {
            return $due_date->toDateString() === $this->task->due_date->toDateString();
        })
        ->assertSet('showTaskFormModal', true);
});

it('resets the form if a task id null is given', function () {
    Livewire::actingAs($this->user)
        ->test(TaskModal::class, [
            'workstream' => $this->workstream,
        ])
        ->call('load', $this->task->id)
        ->assertSet('form.name', $this->task->name)
        ->call('load', null)
        ->assertSet('form.id', null)
        ->assertSet('form.name', '')
        ->assertSet('form.description', '')
        ->assertSet('form.priority_id', $this->configService->get(ConfigEnum::TASK_DEFAULT_PRIORITY_ID))
        ->assertSet('form.due_date', null);
});

it('is listeing for load-task-form-modal event', function () {
    $workstreamModal = Livewire::actingAs($this->user)
        ->test(TaskModal::class, [
            'workstream' => $this->workstream,
        ]);

    $workstreamModal
        ->dispatch('load-task-form-modal', taskId: $this->task->id)
        ->assertSet('form.id', $this->task->id)
        ->assertSet('form.name', $this->task->name)
        ->assertSet('form.description', $this->task->description)
        ->assertSet('showTaskFormModal', true);
});

it('resets form when modal is closed', function () {
    Livewire::actingAs($this->user)
        ->test(TaskModal::class, [
            'workstream' => $this->workstream,
        ])
        ->set('form.id', $this->task->id)
        ->set('form.name', $this->task->name)
        ->set('form.description', $this->task->description)
        ->call('onModalClose')
        ->assertSet('form.id', null)
        ->assertSet('form.name', '')
        ->assertSet('form.description', null)
        ->assertSet('form.due_date', null);
});

it('validates', function () {

    Livewire::actingAs($this->user)
        ->test(TaskModal::class, [
            'workstream' => $this->workstream,
        ])
        ->set('form.priority_id', null)
        ->call('save')
        ->assertHasErrors([
            'form.name' => ['The name field is required.'],
            'form.priority_id' => ['The priority id field is required.'],
        ]);
});

it('created a task', function () {
    $task = $this->workstream->tasks()->where('name', 'New Task Name 123')->first();
    expect($task)->toBeNull();

    $date = Carbon::now();
    Livewire::actingAs($this->user)
        ->test(TaskModal::class, [
            'workstream' => $this->workstream,
        ])
        ->set('form.name', 'New Task Name 123')
        ->set('form.description', 'Description of the task')
        ->set('form.priority_id', 7)
        ->set('form.due_date', $date)
        ->call('save');

    $task = $this->workstream->tasks()->where('name', 'New Task Name 123')->first();

    expect($task->name)->toBe('New Task Name 123');
    expect($task->description)->toBe('Description of the task');
    expect($task->priority_id)->toBe(7);
    expect($task->due_date->toDateString())->toBe($date->toDateString());
});

it('updates a task', function () {
    $date = Carbon::now();
    Livewire::actingAs($this->user)
        ->test(TaskModal::class, [
            'workstream' => $this->workstream,
        ])
        ->call('load', taskId: $this->task->id)
        ->set('form.name', 'New Task Name 123')
        ->set('form.description', 'this is the new description')
        ->set('form.priority_id', 6)
        ->set('form.due_date', $date)
        ->call('save');

    $this->task->refresh();

    expect($this->task->name)->toBe('New Task Name 123');
    expect($this->task->description)->toBe('this is the new description');
    expect($this->task->priority_id)->toBe(6);
    expect($this->task->due_date->toDateString())->toBe($date->toDateString());
});
