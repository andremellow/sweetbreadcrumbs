<?php

use App\Actions\CreateOrganization;
use App\Actions\UpdateMeeting;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    // Create user and organization
    $this->user = User::factory()->create();
    $this->organization = app(CreateOrganization::class)($this->user, 'New Organization Name');

    // Create project and meeting
    $this->project = Project::factory()->for($this->organization)->create();
    $this->meeting = Meeting::factory()->for($this->project)->create([
        'name' => 'Initial Meeting',
        'description' => 'Initial Description',
        'date' => Carbon::now()->addDays(3),
    ]);
});

it('updates a meeting with new values', function () {
    $newDate = Carbon::now()->addWeeks(2);

    // Call UpdateMeeting action
    $updatedMeeting = app(UpdateMeeting::class)(
        $this->project,
        $this->meeting->id,
        'Updated Meeting Name',
        'Updated Description',
        $newDate
    );

    $updatedMeeting->refresh();

    // Assertions
    expect($updatedMeeting)->toBeInstanceOf(Meeting::class);
    expect($updatedMeeting->id)->toBe($this->meeting->id);
    expect($updatedMeeting->project_id)->toBe($this->project->id);
    expect($updatedMeeting->name)->toBe('Updated Meeting Name');
    expect($updatedMeeting->description)->toBe('Updated Description');
    expect($updatedMeeting->date->toDateString())->toBe($newDate->toDateString());
});
