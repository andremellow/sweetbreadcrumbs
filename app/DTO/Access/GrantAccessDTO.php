<?php

namespace App\DTO\Access;

use App\Contracts\AccessibleContract;
use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class GrantAccessDTO extends Data
{
    public function __construct(
        public AccessibleContract $accessible,
        public User $user,
        public RoleEnum $role,
    ) {}

    public static function rules(): array
    {
        return [
            'role' => [Rule::enum(RoleEnum::class)],
        ];
    }
}
