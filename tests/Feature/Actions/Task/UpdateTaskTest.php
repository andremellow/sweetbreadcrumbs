<?php

use App\Actions\Task\OpenTask;
use App\Actions\Task\UpdateTask;
use App\DTO\Task\UpdateTaskDTO;
use App\Models\Task;
use App\Models\User;
use App\Models\Workstream;
use Carbon\Carbon;

beforeEach(function () {
    // Create user and organization
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;

    // Create workstream and task
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();
    $this->task = Task::factory()->for($this->workstream, 'taskable')->withPriority($this->organization)->create();
});

it('Create a task with required fields only', function () {
    // Call OpenTask action
    app(UpdateTask::class)(
        new UpdateTaskDTO(
            user: $this->user,
            task_id: $this->task->id,
            name: 'My new New task',
            priority_id: 10,
        )
    );

    $this->task->refresh();

    // Assertions
    expect($this->task)->toBeInstanceOf(Task::class);
    expect($this->task->taskable->id)->toBe($this->workstream->id);
    expect($this->task->name)->toBe('My new New task');
    expect($this->task->description)->toBeNull();
    expect($this->task->priority_id)->toBe(10);
    expect($this->task->due_date)->toBeNull();
    expect($this->task->completed_at)->toBeNull();
});

it('Create a task', function () {
    app(UpdateTask::class)(
        new UpdateTaskDTO(
            user: $this->user,
            task_id: $this->task->id,
            name: 'My New task updated',
            description: 'My task description updated',
            priority_id: 8,
            due_date: Carbon::now()->addDays(10),
        )
    );
    $this->task->refresh();

    // Assertions
    expect($this->task)->toBeInstanceOf(Task::class);
    expect($this->task->taskable->id)->toBe($this->workstream->id);
    expect($this->task->name)->toBe('My New task updated');
    expect($this->task->description)->toBe('My task description updated');
    expect($this->task->priority_id)->toBe(8);
    expect($this->task->due_date->toDateString())->toBe(Carbon::now()->addDays(10)->toDateString());
    expect($this->task->completed_at)->toBeNull();
});
