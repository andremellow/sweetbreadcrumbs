<?php

namespace App\Actions\Invite;

use App\DTO\Invite\CreateInviteDTO as InviteCreateInviteDTO;
use App\DTO\Workstream\CreateInviteDTO;
use App\Models\Invite;
use Carbon\Carbon;

class CreateInvite
{
    /**
     * Creates new workstream.
     *
     * @param CreateInviteDTo $createInviteDTO
     *
     * @return Invite
     */
    public function __invoke(
        InviteCreateInviteDTO $createInviteDTO
    ): Invite {

        return $createInviteDTO->organization->invites()->create([
            'email' => $createInviteDTO->email,
            'sent_at' => Carbon::now(),
            'role_id' => $createInviteDTO->role_id,
        ]);

    }
}
