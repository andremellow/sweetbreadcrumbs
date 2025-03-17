<?php

use App\Actions\Meeting\DeleteMeeting;
use App\Actions\Organization\CreateOrganization;
use App\DTO\Meeting\DeleteMeetingDTO;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\Meeting;
use App\Models\User;
use App\Models\Workstream;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();
});

it('Soft deletes a meeting', function () {
    $meeting = Meeting::factory()->for($this->workstream)->create();
    expect($meeting->deleted_at)->toBeNull();

    app(DeleteMeeting::class)(
        new DeleteMeetingDTO($meeting)
    );

    $meeting->refresh();

    expect($meeting->deleted_at)->not->toBeNull();
});
