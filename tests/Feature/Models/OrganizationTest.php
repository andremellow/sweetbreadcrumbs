<?php

use App\Models\Invite;
use App\Models\Meeting;
use App\Models\Priority;
use App\Models\Role;
use App\Models\Task;
use App\Models\Workstream;

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;
    Invite::factory()->for($this->organization)->for($this->user, 'inviter')->withRole($this->organization)->create();
    $workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();
    Task::factory()->for($workstream, 'taskable')->withPriority($this->organization)->create();
    Meeting::factory()->for($workstream)->create();

});

it('has relationships', function () {
    expect($this->organization->priorities()->first())->toBeInstanceOf(Priority::class);
    expect($this->organization->workstreams()->first())->toBeInstanceOf(Workstream::class);
    expect($this->organization->invites()->first())->toBeInstanceOf(Invite::class);
    expect($this->organization->roles()->first())->toBeInstanceOf(Role::class);
    expect($this->organization->meetings()->first())->toBeInstanceOf(Meeting::class);
    expect($this->organization->tasks()->first())->toBeInstanceOf(Task::class);
});
