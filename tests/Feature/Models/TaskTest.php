<?php

use App\Models\Task;
use App\Models\User;
use App\Models\Workstream;
use Carbon\Carbon;

beforeEach(function () {
    // Create user and organization
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;

    // Create workstream and meeting
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();

});

it('has isCompleted attribute working as expected', function () {
    $this->task = Task::factory()->for($this->workstream, 'taskable')->create([
        'name' => 'Initial Meeting',
        'description' => 'Initial Description',
        'priority_id' => 6,
    ]);

    expect($this->task->is_completed)->toBe(false);
    $this->task->update(['completed_at' => Carbon::now()]);
    $this->task->refresh();
    expect($this->task->is_completed)->toBe(true);
});

it('has isLate attribute working as expected', function () {
    $this->task = Task::factory()->for($this->workstream, 'taskable')->create([
        'name' => 'Initial Meeting',
        'description' => 'Initial Description',
        'priority_id' => 6,
        'due_date' => Carbon::now()->addDay(1),
    ]);

    expect($this->task->is_late)->toBe(false);

    // Make it late
    $this->task->update(['due_date' => Carbon::now()->addDay(-1)]);
    $this->task->refresh();
    expect($this->task->is_late)->toBe(true);

    // Make it completed. Completed task are not late
    $this->task->update(['completed_at' => Carbon::now()->addDay(-1)]);
    $this->task->refresh();
    expect($this->task->is_late)->toBe(false);
});
