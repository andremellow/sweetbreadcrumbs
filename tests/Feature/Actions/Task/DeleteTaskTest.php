<?php

use App\Actions\Task\DeleteTask;
use App\DTO\Task\DeleteTaskDTO;
use App\Models\Task;
use App\Models\User;
use App\Models\Workstream;

beforeEach(function () {
    // Create user and organization
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;

    // Create workstream and task
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();
    $this->task = Task::factory()->for($this->workstream, 'taskable')->withPriority($this->organization)->create();
});

it('deletes a task', function () {
    app(DeleteTask::class)(
        new DeleteTaskDTO(
            user: $this->user,
            task_id: $this->task->id,
        )
    );

    $this->task->refresh();
    expect($this->task->deleted_at)->not->toBeNull();
});
