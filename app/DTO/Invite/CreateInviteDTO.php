<?php

namespace App\DTO\Invite;

use App\Models\Organization;
use App\Models\User;
use Spatie\LaravelData\Data;

class CreateInviteDTO extends Data
{
    public function __construct(
        public User $user,
        public Organization $organization,
        public string $email,
        public int $role_id,
    ) {}

    public static function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'role_id' => ['required'],
        ];
    }
}
