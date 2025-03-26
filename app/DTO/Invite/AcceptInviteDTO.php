<?php

namespace App\DTO\Invite;

use App\Models\Invite;
use App\Models\User;
use Spatie\LaravelData\Data;

class AcceptInviteDTO extends Data
{
    public function __construct(
        public User $user,
        public Invite $invite,
    ) {}
}
