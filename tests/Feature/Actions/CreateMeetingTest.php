<?php

use App\Actions\CreateMeeting;
use App\Actions\CreateOrganization;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = app(CreateOrganization::class)($this->user, 'New Organization Name');
    $this->project = Project::factory()->for($this->organization)->create();
});

it('creates a meeting with required fields only', function () {
    $date = Carbon::now()->addWeek();

    $meeting = app(CreateMeeting::class)(
        $this->project,
        'Retrospective',
        'Discussion of past sprint',
        $date
    );

    $meeting->refresh();

    expect($meeting)->toBeInstanceOf(Meeting::class);
    expect($meeting->project_id)->toBe($this->project->id);
    expect($meeting->name)->toBe('Retrospective');
    expect($meeting->description)->toBe('Discussion of past sprint');
    expect($meeting->date->toDateString())->toBe($date->toDateString());
});
