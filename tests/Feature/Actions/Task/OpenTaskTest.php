<?php

use App\Actions\Organization\CreateOrganization;
use App\Actions\Task\OpenTask;
use App\DTO\Organization\CreateOrganizationDTO;
use App\DTO\Task\OpenTaskDTO;
use App\Models\Meeting;
use App\Models\Task;
use App\Models\User;
use App\Models\Workstream;
use Carbon\Carbon;

beforeEach(function () {
    // Create user and organization
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));

    // Create workstream and meeting
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();
    $this->task = Task::factory()->for($this->workstream, 'taskable')->create([
        'name' => 'Initial Meeting',
        'description' => 'Initial Description',
        'priority_id' => 6,
        'due_date' => Carbon::now()->addDays(3),
        'completed_at' => Carbon::now()->addDays(3),
    ]);
});

it('Open a meeting ', function () {
    // Call OpenTask action
    $updatedTask = app(OpenTask::class)(
        new OpenTaskDTO(
            user: $this->user,
            task_id: $this->task->id,
        )
    );

    $updatedTask->refresh();

    // Assertions
    expect($updatedTask)->toBeInstanceOf(Task::class);
    expect($updatedTask->id)->toBe($this->task->id);
    expect($updatedTask->taskable->id)->toBe($this->workstream->id);
    expect($updatedTask->completed_at)->toBeNull();
});
