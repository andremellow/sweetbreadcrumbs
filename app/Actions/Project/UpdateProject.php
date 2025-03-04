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
            'release_plan' => $updateProjectDTO->release_plan,
            'technical_documentation' => $updateProjectDTO->technical_documentation,
            'needs_to_start_by' => $updateProjectDTO->needs_to_start_by,
            'needs_to_deployed_by' => $updateProjectDTO->needs_to_deployed_by,
        ]);

        return $project;

    }
}
