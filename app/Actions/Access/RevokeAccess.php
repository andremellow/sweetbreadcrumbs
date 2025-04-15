<?php

namespace App\Actions\Access;

use App\DTO\Access\RevokeAccessDTO;

class RevokeAccess
{
    /**
     * Revoke Access.
     *
     * @param RevokeAccessDTO $revokeAccessDTO ,
     *
     */
    public function __invoke(RevokeAccessDTO $revokeAccessDTO): void
    {

        $revokeAccessDTO
            ->accessible
            ->accesses()
            ->where('user_id', $revokeAccessDTO->user_id)
            ->delete();
    }
}
