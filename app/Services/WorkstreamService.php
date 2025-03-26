<?php

namespace App\Services;

use App\Actions\Workstream\CreateWorkstream;
use App\Actions\Workstream\DeleteWorkstream;
use App\Actions\Workstream\UpdateWorkstream;
use App\DTO\Workstream\CreateWorkstreamDTO;
use App\DTO\Workstream\DeleteWorkstreamDTO;
use App\DTO\Workstream\UpdateWorkstreamDTO;
use App\Enums\SortDirection;
use App\Models\Organization;
use App\Models\User;
use App\Models\Workstream;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class WorkstreamService
{
    /**
     * WorkstreamService Construct.
     *
     * @param CreateWorkstream $createWorkstream
     *
     * @return WorkstreamService
     */
    public function __construct(protected CreateWorkstream $createWorkstream, protected UpdateWorkstream $updateWorkstream, protected DeleteWorkstream $deleteWorkstream) {}

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

        return $organization->workstreams()
            ->leftJoin('priorities', 'workstreams.priority_id', '=', 'priorities.id')
            ->with(['priority:id,name'])
            ->when($name, function (Builder $query, string $name): builder {
                return $query->where('workstreams.name', 'like', "%$name%");
            })
            ->when($priorityId, function (builder $query, int $priorityId): builder {
                return $query->where('workstreams.priority_id', '=', $priorityId);
            })
            ->orderBy($sortBy, $sortDirection->value)
            ->select('workstreams.*') // make sure to select workstreams columns
            ->paginate(config('app.pagination_items'));
    }

    /**
     * Creates a new workstream.
     *
     * @param Use                 $user,
     * @param CreateWorkstreamDTO $createWorkstreamDTO,
     *
     * @return Workstream
     */
    public function create(
        User $user,
        CreateWorkstreamDTO $createWorkstreamDTO
    ): Workstream {

        return ($this->createWorkstream)(
            $createWorkstreamDTO
        );
    }

    /**
     * Update an existing workstream.
     *
     * @param UpdateWorkstreamDTO $updateWorkstreamDTO
     *
     * @return Workstream
     */
    public function update(
        User $user,
        UpdateWorkstreamDTO $updateWorkstreamDTO
    ): Workstream {
        return ($this->updateWorkstream)(
            $updateWorkstreamDTO
        );
    }

    /**
     * Delete an existing workstream.
     *
     * @param User                $user,
     * @param DeleteWorkstreamDTO $deleteWorkstreamDTO
     *
     * @return Workstream
     */
    public function delete(
        User $user,
        DeleteWorkstreamDTO $deleteWorkstreamDTO
    ): void {
        ($this->deleteWorkstream)(
            $deleteWorkstreamDTO
        );
    }
}
