<?php

namespace App\DTO\Project;

use App\Models\Organization;
use Spatie\LaravelData\Data;

class UpdateProjectDTO extends Data
{
    public function __construct(
        public Organization $organization,
        public int $project_id,
        public string $name,
        public ?int $priority_id
    ) {}

    public static function rules(): array
    {
        return [
            'project_id' => ['nullable', 'integer'],
            ...CreateProjectDTO::rules(),
        ];
    }
}
