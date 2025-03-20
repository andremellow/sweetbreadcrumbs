<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\Invite;
use App\Models\Meeting;
use App\Models\Priority;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Models\Workstream;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));
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
