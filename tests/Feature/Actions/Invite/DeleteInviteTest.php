<?php

use App\Actions\Invite\DeleteInvite;
use App\DTO\Invite\DeleteInviteDTO;
use App\Models\Invite;

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;
});

it('Soft deletes a meeting', function () {
    $invite = Invite::factory()->for($this->organization)->for($this->user, 'inviter')->withRole($this->organization)->create();

    (new DeleteInvite)(
        new DeleteInviteDTO(
            user: $this->user,
            organization: $this->organization,
            invite_id: $invite->id
        )
    );

    $invite = Invite::find($invite->id);

    expect($invite)->toBeNull();
});
