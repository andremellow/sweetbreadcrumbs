<?php

namespace App\Actions\Project;

use App\DTO\Project\CreateProjectDTO;
use App\Models\Project;

class CreateProject
{
    /**
     * Creates new project.
     *
     * @param CreateProjectDTO $createProjectDTO
     *
     * @return Project
     */
    public function __invoke(
        CreateProjectDTO $createProjectDTO
    ): Project {

        return $createProjectDTO->organization->projects()->create([
            'name' => $createProjectDTO->name,
            'priority_id' => $createProjectDTO->priority_id,
        ]);

    }
}
