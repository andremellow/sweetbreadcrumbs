<?php

use App\Actions\Invite\CreateInvite;
use App\DTO\Invite\CreateInviteDTO;

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;
});

it('creates a new invite with required with all fields', function () {

    $invite = app(CreateInvite::class)(
        CreateInviteDTO::from([
            'user' => $this->user,
            'organization' => $this->organization,
            ...[
                'email' => 'johndoe@gmail.com',
                'role_id' => 2,
            ],
        ])
    );

    $invite->refresh();

    expect($invite->organization_id)->toBe($this->organization->id);
    expect($invite->email)->toBe('johndoe@gmail.com');
    expect($invite->role_id)->toBe(2);
});
