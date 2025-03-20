<?php

namespace App\DTO\Invite;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class CreateInviteDTO extends Data
{
    public function __construct(
        public User $user,
        public Organization $organization,
        public string $email,
        public int $role_id,
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return static::rawRules($context->payload['organization']->id);
    }

    public static function rawRules($organizationId): array
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique('invites')->where(fn (Builder $query) => $query->where('organization_id', $organizationId))],
            'role_id' => ['required',
                Rule::exists('App\Models\Role', 'id')->where(fn (Builder $query) => $query->where('organization_id', $organizationId))],
        ];
    }
}
