<?php

namespace App\DTO\Project;

use App\Models\Organization;
use Spatie\LaravelData\Data;

class CreateProjectDTO extends Data
{
    public function __construct(
        public Organization $organization,
        public string $name,
        public ?int $priority_id
    ) {}

    public static function rules(): array
    {
        return [
            'name' => ['required', 'min:2', 'max:50'],
            'priority_id' => ['required', 'integer'],
        ];
    }
}
