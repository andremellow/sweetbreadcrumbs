<?php

use App\Actions\Invite\CreateInvite;
use App\Actions\Organization\CreateOrganization;
use App\DTO\Invite\CreateInviteDTO;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));
});

it('creates a new invite with required with all fields', function () {
    $role = $this->organization->roles()->first();

    $invite = app(CreateInvite::class)(
        CreateInviteDTO::from([
            'user' => $this->user,
            'organization' => $this->organization,
            ...[
                'email' => 'johndoe@gmail.com',
                'role_id' => $role->id,
            ],
        ])
    );

    $invite->refresh();

    expect($invite->organization_id)->toBe($this->organization->id);
    expect($invite->email)->toBe('johndoe@gmail.com');
    expect($invite->role_id)->toBe($role->id);
});
