<?php

use App\Actions\Organization\CreateOrganization;
use App\Actions\Task\CreateTask;
use App\DTO\Organization\CreateOrganizationDTO;
use App\DTO\Task\CreateTaskDTO;
use App\Models\Workstream;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    // Create user and organization
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));

    // Create workstream and meeting
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();
});

it('Create a meeting with required fields only', function () {
    // Call OpenTask action
    $task = app(CreateTask::class)(
        new CreateTaskDTO(
            user: $this->user,
            taskable: $this->workstream,
            name: 'My New task',
            priority_id: 7,
        )
    );

    // Assertions
    expect($task)->toBeInstanceOf(Task::class);
    expect($task->taskable->id)->toBe($this->workstream->id);
    expect($task->name)->toBe('My New task');
    expect($task->description)->toBeNull();
    expect($task->priority_id)->toBe(7);
    expect($task->due_date)->toBeNull();
    expect($task->completed_at)->toBeNull();
});

it('Create a meeting', function () {
    // Call OpenTask action
    $task = app(CreateTask::class)(
        new CreateTaskDTO(
            user: $this->user,
            taskable: $this->workstream,
            name: 'My New task',
            description: 'My task descriptioni',
            priority_id: 7,
            due_date: Carbon::now()->addDays(7),
        )
    );

    // Assertions
    expect($task)->toBeInstanceOf(Task::class);
    expect($task->taskable->id)->toBe($this->workstream->id);
    expect($task->name)->toBe('My New task');
    expect($task->description)->toBe('My task descriptioni');
    expect($task->priority_id)->toBe(7);
    expect($task->due_date->toDateString())->toBe(Carbon::now()->addDays(7)->toDateString());
    expect($task->completed_at)->toBeNull();
});
