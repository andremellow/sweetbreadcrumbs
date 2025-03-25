<?php

use App\Actions\Meeting\UpdateMeeting;
use App\DTO\Meeting\UpdateMeetingDTO;
use App\Models\Meeting;
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
    $this->meeting = Meeting::factory()->for($this->workstream)->create([
        'name' => 'Initial Meeting',
        'description' => 'Initial Description',
        'date' => Carbon::now()->addDays(3),
    ]);
});

it('updates a meeting with new values', function () {
    $newDate = Carbon::now()->addWeeks(2);

    // Call UpdateMeeting action
    $updatedMeeting = app(UpdateMeeting::class)(
        new UpdateMeetingDTO(
            $this->workstream,
            $this->meeting->id,
            'Updated Meeting Name',
            'Updated Description',
            $newDate
        )
    );

    $updatedMeeting->refresh();

    // Assertions
    expect($updatedMeeting)->toBeInstanceOf(Meeting::class);
    expect($updatedMeeting->id)->toBe($this->meeting->id);
    expect($updatedMeeting->workstream_id)->toBe($this->workstream->id);
    expect($updatedMeeting->name)->toBe('Updated Meeting Name');
    expect($updatedMeeting->description)->toBe('Updated Description');
    expect($updatedMeeting->date->toDateString())->toBe($newDate->toDateString());
});
