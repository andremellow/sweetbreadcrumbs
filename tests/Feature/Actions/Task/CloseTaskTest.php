<?php

use App\Actions\Meeting\UpdateMeeting;
use App\Actions\Organization\CreateOrganization;
use App\Actions\Task\CloseTask;
use App\DTO\Meeting\UpdateMeetingDTO;
use App\DTO\Organization\CreateOrganizationDTO;
use App\DTO\Task\CloseTaskDTO;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    // Create user and organization
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));

    // Create project and meeting
    $this->project = Project::factory()->for($this->organization)->withPriority($this->organization)->create();
    $this->task = Task::factory()->for($this->project)->create([
        'name' => 'Initial Meeting',
        'description' => 'Initial Description',
        'priority_id' => 6,
        'due_date' => Carbon::now()->addDays(3),
    ]);
});

it('Close a meeting ', function () {
    $newDate = Carbon::now()->addWeeks(2);

    // Call CloseTask action
    $updatedTask = app(CloseTask::class)(
        new CloseTaskDTO(
            user: $this->user,
            task_id: $this->task->id,
        )
    );

    $updatedTask->refresh();

    // Assertions
    expect($updatedTask)->toBeInstanceOf(Task::class);
    expect($updatedTask->id)->toBe($this->task->id);
    expect($updatedTask->project_id)->toBe($this->project->id);
    expect($updatedTask->completed_at->toDateString())->toBe(Carbon::now()->toDateString());

});
