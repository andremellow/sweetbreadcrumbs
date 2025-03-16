<?php

namespace App\DTO\Organization;

use Spatie\LaravelData\Data;

class CreateOrganizationDTO extends Data
{
    public function __construct(
        public string $name,
    ) {}

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:50', 'unique:organizations'],
        ];
    }
}
