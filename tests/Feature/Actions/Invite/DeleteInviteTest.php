<?php

use App\Actions\Invite\DeleteInvite;
use App\Actions\Organization\CreateOrganization;
use App\DTO\Invite\DeleteInviteDTO;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\Invite;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));
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
