<?php

namespace App\DTO\Project;

use App\Models\Project;
use Spatie\LaravelData\Data;

class DeleteProjectDTO extends Data
{
    public function __construct(
        public Project $project
    ) {}
}
