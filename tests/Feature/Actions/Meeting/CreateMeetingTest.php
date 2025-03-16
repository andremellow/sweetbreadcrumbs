<?php

use App\Actions\Meeting\CreateMeeting;
use App\Actions\Organization\CreateOrganization;
use App\DTO\Meeting\CreateMeetingDTO;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\Meeting;
use App\Models\Workstream;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();
});

it('creates a meeting with required fields only', function () {
    $date = Carbon::now()->addWeek();

    $meeting = app(CreateMeeting::class)(
        CreateMeetingDTO::from([
            'workstream' => $this->workstream,
            'name' => 'Retrospective',
            'description' => 'Discussion of past sprint',
            'date' => $date->format(config('app.save_date_format')),
        ])
    );

    $meeting->refresh();

    expect($meeting)->toBeInstanceOf(Meeting::class);
    expect($meeting->workstream_id)->toBe($this->workstream->id);
    expect($meeting->name)->toBe('Retrospective');
    expect($meeting->description)->toBe('Discussion of past sprint');
    expect($meeting->date->toDateString())->toBe($date->toDateString());
});
