<?php

namespace App\DTO\Access;

use App\Contracts\AccessibleContract;
use App\Models\User;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class RevokeAccessDTO extends Data
{
    public function __construct(
        public AccessibleContract $accessible,
        public int $user_id,
    ) {}

    public static function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', Rule::exists(User::class, 'id')],
        ];
    }
}
