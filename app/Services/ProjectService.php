<?php

namespace App\Services;

use App\Actions\Project\CreateProject;
use App\Actions\Project\DeleteProject;
use App\Actions\Project\UpdateProject;
use App\DTO\Project\CreateProjectDTO;
use App\DTO\Project\DeleteProjectDTO;
use App\DTO\Project\UpdateProjectDTO;
use App\Enums\SortDirection;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
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
    public function __construct(protected CreateProject $createProject, protected UpdateProject $updateProject, protected DeleteProject $deleteProject) {}

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
            case 'date':
                $sortBy = 'created_at';
                break;
            default:
                $sortBy = 'name';
                break;
        }

        return $organization->projects()
            ->leftJoin('priorities', 'projects.priority_id', '=', 'priorities.id')
            ->with(['priority:id,name'])
            ->when($name, function ($query, $name) {
                return $query->where('projects.name', 'like', "%$name%");
            })
            ->when($priorityId, function ($query, $priorityId) {
                return $query->where('projects.priority_id', '=', $priorityId);
            })
            ->orderBy($sortBy, $sortDirection->value)
            ->select('projects.*') // make sure to select projects columns
            ->paginate(config('app.pagination_items'));
    }

    /**
     * Creates a new project.
     *
     * @param Use              $user,
     * @param CreateProjectDTO $createProjectDTO,
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

    /**
     * Delete an existing project.
     *
     * @param User             $user,
     * @param DeleteProjectDTO $deleteProjectDTO
     *
     * @return Project
     */
    public function delete(
        User $user,
        DeleteProjectDTO $deleteProjectDTO
    ): void {
        ($this->deleteProject)(
            $deleteProjectDTO
        );
    }
}
