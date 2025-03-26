<?php

use App\Actions\Meeting\DeleteMeeting;
use App\DTO\Meeting\DeleteMeetingDTO;
use App\Models\Meeting;
use App\Models\Workstream;

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;
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
