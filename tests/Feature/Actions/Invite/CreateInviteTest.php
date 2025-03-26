<?php

use App\Actions\Invite\CreateInvite;
use App\DTO\Invite\CreateInviteDTO;

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;
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
