<?php

namespace App\Actions\Project;

use App\DTO\Project\DeleteProjectDTO;
use App\Models\Project;

class DeleteProject
{
    /**
     * Delete exiting Project.
     *
     * @param DeleteProjectDTO $deleteProjectDTO=
     *
     * @return Delete
     */
    public function __invoke(
        DeleteProjectDTO $deleteProjectDTO
    ): void {
        $deleteProjectDTO->project->delete();
    }
}
