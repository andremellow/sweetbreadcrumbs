<?php

namespace App\DTO\Invite;

use App\Models\Organization;
use App\Models\User;
use Spatie\LaravelData\Data;

class DeleteInviteDTO extends Data
{
    public function __construct(
        public User $user,
        public Organization $organization,
        public int $invite_id,
    ) {}

    public static function rules(): array
    {
        return [
            'invite_id' => ['required'],
        ];
    }
}
