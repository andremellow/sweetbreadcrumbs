<?php

namespace App\Actions\Project;

use App\DTO\Project\UpdateProjectDTO;
use App\Models\Project;

class UpdateProject
{
    /**
     * Update exiting Project.
     *
     * @param UpdateProjectDTO $updateProjectDTO=
     *
     * @return Project
     */
    public function __invoke(
        UpdateProjectDTO $updateProjectDTO
    ): Project {

        $project = $updateProjectDTO->organization->projects()->findOrFail($updateProjectDTO->project_id);
        $project->update([
            'name' => $updateProjectDTO->name,
            'priority_id' => $updateProjectDTO->priority_id,
        ]);

        return $project;

    }
}
