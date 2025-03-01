<?php

namespace App\Services;

use App\Actions\CreateProject;
use App\Actions\UpdateProject;
use App\Enums\SortDirection;
use App\Models\Organization;
use App\Models\Project;
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
        Organization $organization,
        string $name,
        int $priorityId,
        ?int $toggleOnByReleaseId,
        ?string $releasePlan,
        ?string $technicalDocumentation,
        Carbon|string|null $needsToStartBy,
        Carbon|string|null $needsToDeployedBy,
    ): Project {

        return ($this->createProject)(
            $organization,
            $name,
            $priorityId,
            $toggleOnByReleaseId,
            $releasePlan,
            $technicalDocumentation,
            $this->maybeParseToCarbon($needsToStartBy),
            $this->maybeParseToCarbon($needsToDeployedBy),
        );
    }

    /**
     * Update an existing project.
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
    public function update(
        Organization $organization,
        int $projectId,
        string $name,
        int $priorityId,
        ?int $toggleOnByReleaseId,
        ?string $releasePlan,
        ?string $technicalDocumentation,
        Carbon|string|null $needsToStartBy,
        Carbon|string|null $needsToDeployedBy,
    ): Project {
        return ($this->updateProject)(
            $organization,
            $projectId,
            $name,
            $priorityId,
            $toggleOnByReleaseId,
            $releasePlan,
            $technicalDocumentation,
            $this->maybeParseToCarbon($needsToStartBy),
            $this->maybeParseToCarbon($needsToDeployedBy),
        );
    }

    protected function maybeParseToCarbon(string|Carbon|null $maybeADate): ?Carbon
    {
        if (! ($maybeADate instanceof Carbon) && ! is_null($maybeADate)) {
            return Carbon::parse($maybeADate);
        }

        return null;
    }

    public function list(
        Organization $organization,
        ?string $name,
        ?int $priorityId,
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
