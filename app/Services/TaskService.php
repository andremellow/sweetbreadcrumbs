<?php

namespace App\Services;

use App\Actions\Meeting\CreateMeeting;
use App\Actions\Meeting\DeleteMeeting;
use App\Actions\Meeting\UpdateMeeting;
use App\DTO\Meeting\CreateMeetingDTO;
use App\DTO\Meeting\DeleteMeetingDTO;
use App\DTO\Meeting\UpdateMeetingDTO;
use App\Enums\SortDirection;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService
{
    public function list(
        Project $project,
        ?string $search = null,
        ?int $priorityId = null,
        ?string $status = null,
        ?Carbon $dateStart = null,
        ?Carbon $dateEnd = null,
        ?string $sortBy = 'name',
        ?SortDirection $sortDirection = SortDirection::ASC
    ): LengthAwarePaginator {

        if (! in_array($sortBy, ['name', 'due_date', 'priority'])) {
            $sortBy = 'name';
        }

        switch ($sortBy) {
            case 'priority':
                $sortBy = 'priorities.order';
                break;
        }

        return $project->tasks()->with('priority')
            ->leftJoin('priorities', 'tasks.priority_id', '=', 'priorities.id')
            ->when($search, function ($query, $search) {
                $query->whereLike('tasks.name', "%$search%")
                    ->orWhereLike('tasks.description', "%$search%");
            })
            ->when($priorityId, function ($query, $priorityId) {
                return $query->where('tasks.priority_id', '=', $priorityId);
            })
            ->when($status, function ($query, $status) {
                if($status === 'open') {
                    return $query->whereNull('tasks.completed_at');
                } else if($status === 'closed') {
                    return $query->whereNotNull('tasks.completed_at');
                }
            })
            ->when($dateStart, function ($query, $dateStart) {
                return $query->whereDate('tasks.due_date', '>=', $dateStart);
            })
            ->when($dateEnd, function ($query, $dateEnd) {
                return $query->whereDate('tasks.due_date', '<=', $dateEnd);
            })
            ->orderBy($sortBy, $sortDirection->value)
            ->select('tasks.*') // make sure to select projects columns
            ->paginate(config('app.pagination_items'));
    }

    public function open(User $user, int $taskId): void
    {
        Task::find($taskId)->update([ 'completed_at' => null ]);
    }

    public function close(User $user, int $taskId): void
    {
        Task::find($taskId)->update([ 'completed_at' => Carbon::now() ]);
    }

    // public function lastMeeings(
    //     Project $project,
    //     int $take = 5
    // ) {

    //     return $project->meetings()
    //             ->orderBy('date', SortDirection::DESC->value)
    //             ->take($take)
    //             ->get();
    // }

    /**
     * MeetingService Construct.
     *
     * @param CreateMeeting $createMeeting
     *
     * @return MeetingService
     */
    public function __construct() {}
    // public function __construct(protected CreateMeeting $createMeeting, protected UpdateMeeting $updateMeeting, protected DeleteMeeting $deleteMeeting) {}

    /**
     * Creates a new meeting.
     *
     * @param User             $user,
     * @param CreateMeetingDTO $createMeetingDTO,
     *
     * @return Meeting
     */
    // public function create(
    //     User $user,
    //     CreateMeetingDTO $createMeetingDTO
    // ): Meeting {
    //     return ($this->createMeeting)($createMeetingDTO);
    // }

    /**
     * Update an existing meeting.
     *
     * @param UpdateMeetingDTO $updateMeetingDTO
     *
     * @return Meeting
     */
    // public function update(
    //     User $user,
    //     UpdateMeetingDTO $updateMeetingDTO
    // ): Meeting {
    //     return ($this->updateMeeting)(
    //         $updateMeetingDTO
    //     );
    // }

    /**
     * Delete a new meeting.
     *
     * @param User             $user,
     * @param DeleteMeetingDTO $deleteMeetingDTO,
     *
     * @return void
     */
    // public function delete(
    //     User $user,
    //     DeleteMeetingDTO $deleteMeetingDTO
    // ): void {
    //     ($this->deleteMeeting)($deleteMeetingDTO);
    // }
}
