<?php

use App\Actions\Organization\CreateOrganization;
use App\Actions\Task\CreateTask;
use App\Actions\Task\OpenTask;
use App\Actions\Task\DeleteTask;
use App\DTO\Organization\CreateOrganizationDTO;
use App\DTO\Task\DeleteTaskDTO;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    // Create user and organization
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));

    // Create project and task
    $this->project = Project::factory()->for($this->organization)->withPriority($this->organization)->create();
    $this->task = Task::factory()->for($this->project, 'taskable')->withPriority($this->organization)->create();
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
