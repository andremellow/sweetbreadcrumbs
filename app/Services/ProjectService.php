<?php

namespace App\Services;

use App\Actions\Project\CreateProject;
use App\Actions\Project\UpdateProject;
use App\DTO\Project\CreateProjectDTO;
use App\DTO\Project\UpdateProjectDTO;
use App\Enums\SortDirection;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProjectService
{
    /**
     * OrganizationService Construct.
     *
     * @param CreateProject $createProject
     *
     * @return OrganizationService
     */
    public function __construct(protected CreateProject $createProject, protected UpdateProject $updateProject) {}

    /**
     * Creates a new project.
     *
     * @param Organization    $organization,
     * @param string          $name,
     * @param int             $priorityId,
     * @param int             $toggleOnByReleaseId,
     * @param string          $releasePlan,
     * @param string          $technicalDocumentation,
     * @param string | Carbon $needsToStartBy,
     * @param string | Carbon $needsToDeployedBy,
     *
     * @return Project
     */
    public function create(
        User $user,
        CreateProjectDTO $createProjectDTO
    ): Project {

        return ($this->createProject)(
            $createProjectDTO
        );
    }

    /**
     * Update an existing project.
     *
     * @param UpdateProjectDTO $updateProjectDTO
     *
     * @return Project
     */
    public function update(
        User $user,
        UpdateProjectDTO $updateProjectDTO
    ): Project {
        return ($this->updateProject)(
            $updateProjectDTO
        );
    }

    public function list(
        Organization $organization,
        ?string $name = null,
        ?int $priorityId = null,
        string $sortBy = 'name',
        SortDirection $sortDirection = SortDirection::ASC
    ): LengthAwarePaginator {
        switch ($sortBy) {
            case 'priority':
                $sortBy = 'priorities.order';
                break;
        }

        return $organization->projects()
            ->leftJoin('priorities', 'projects.priority_id', '=', 'priorities.id')
            ->with(['priority:id,name', 'toggleOnByRelease:id,name'])
            ->when($name, function ($query, $name) {
                return $query->where('projects.name', 'like', "%$name%");
            })
            ->when($priorityId, function ($query, $priorityId) {
                return $query->where('projects.priority_id', '=', $priorityId);
            })
            ->orderBy($sortBy, $sortDirection->value)
            ->select('projects.*') // make sure to select projects columns
            ->paginate(config('app.pagination_items'))
            ->appends([
                'sort_by' => $sortBy,
                'sort_direction' => $sortDirection->value,
                'name' => $name,
                'priority_id' => $priorityId,
            ]);
    }
}
