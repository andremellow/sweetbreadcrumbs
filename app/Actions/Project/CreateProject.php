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
            'toggle_on_by_release_id' => $createProjectDTO->toggle_on_by_release_id,
            'release_plan' => $createProjectDTO->release_plan,
            'technical_documentation' => $createProjectDTO->technical_documentation,
            'needs_to_start_by' => $createProjectDTO->needs_to_start_by,
            'needs_to_deployed_by' => $createProjectDTO->needs_to_deployed_by,
        ]);

    }
}
