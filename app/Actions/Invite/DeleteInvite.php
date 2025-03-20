<?php

namespace App\Actions\Invite;

use App\DTO\Invite\DeleteInviteDTO;
use App\Models\Invite;

class DeleteInvite
{
    /**
     * Delete exiting Invite.
     *
     * @param DeleteInviteDTO $deleteInviteDTO=
     *
     * @return Delete
     */
    public function __invoke(
        DeleteInviteDTO $deleteInviteDTO
    ): void {
        $invite = $deleteInviteDTO->organization->invites()->find($deleteInviteDTO->invite_id);

        $invite->delete();
    }
}
