<?php

namespace App\DTO\Access;

use App\Contracts\AccessibleContract;
use App\Models\User;
use Spatie\LaravelData\Data;

class GrantAccessDTO extends Data
{
    public function __construct(
        public AccessibleContract $accessible,
        public User $user,
        public int $roleId,
    ) {}

    public static function rules(): array
    {
        return [
            'roleId' => ['required', 'integer'],
        ];
    }
}
