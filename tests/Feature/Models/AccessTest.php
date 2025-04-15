<?php

use App\Enums\RoleEnum;
use App\Models\User;
use App\Models\Workstream;

beforeEach(function () {
    // Create user and organization
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;

    // Create workstream and meeting
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();

});

it('has isCompleted attribute working as expected', function () {
    $access = $this->workstream->accesses()->create([
        'user_id' => $this->user->id,
        'role_id' => RoleEnum::ADMIN->value,
    ]);

    expect($access->accessible)->toBeInstanceOf(Workstream::class);

});
