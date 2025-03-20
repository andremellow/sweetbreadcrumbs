<?php

namespace App\Actions\Invite;

use App\DTO\Invite\UpdateInviteSentDTO;
use App\Models\Invite;
use Carbon\Carbon;

class UpdateInviteSent
{
    /**
     * Delete exiting Invite.
     *
     * @param UpdateInviteSentDTO $updateInviteSentDTO
     *
     * @return Delete
     */
    public function __invoke(
        UpdateInviteSentDTO $updateInviteSentDTO
    ): Invite {
        $invite = $updateInviteSentDTO->organization->invites()->find($updateInviteSentDTO->invite_id);

        $invite->update([
            'sent_at' => Carbon::now(),
        ]);

        return $invite;
    }
}
