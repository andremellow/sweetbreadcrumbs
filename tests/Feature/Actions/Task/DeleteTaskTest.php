<?php

use App\Actions\Organization\CreateOrganization;
use App\Actions\Task\DeleteTask;
use App\DTO\Organization\CreateOrganizationDTO;
use App\DTO\Task\DeleteTaskDTO;
use App\Models\Task;
use App\Models\User;
use App\Models\Workstream;

beforeEach(function () {
    // Create user and organization
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));

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
