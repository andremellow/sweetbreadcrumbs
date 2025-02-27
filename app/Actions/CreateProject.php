<?php

namespace App\Actions;

use App\Models\Organization;
use App\Models\Priority;
use App\Models\Probability;
use App\Models\Project;
use App\Models\RiskLevel;
use App\Models\RiskStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CreateProject
{

    /**
     * Creates new project
     *
     * @param Organization $organization,
     * @param string $name,
     * @param int $priorityId,
     * @param int $toggleOnByReleaseId,
     * @param string $releasePlan,
     * @param string $technicalDocumentation,
     * @param Carbon | null $needsToStartBy,
     * @param Carbon | null $needsToDeployedBy,
     * @return Project
     */
    public function __invoke(
        Organization $organization,
        string $name,
        int $priorityId,
        int | null $toggleOnByReleaseId,
        string | null $releasePlan,
        string | null $technicalDocumentation,
        Carbon | null $needsToStartBy,
        Carbon | null $needsToDeployedBy,
    ): Project
    {

        return $organization->projects()->create([
            "name" => $name,
            "priority_id" => $priorityId,
            "toggle_on_by_release_id" => $toggleOnByReleaseId,
            "release_plan" => $releasePlan,
            "technical_documentation" => $technicalDocumentation,
            "needs_to_start_by" => $needsToStartBy,
            "needs_to_deployed_by" => $needsToDeployedBy,
        ]);

    }
}
