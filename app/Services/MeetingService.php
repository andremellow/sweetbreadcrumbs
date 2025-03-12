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
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MeetingService
{
    public function list(
        Project $project,
        ?string $search = null,
        ?Carbon $dateStart = null,
        ?Carbon $dateEnd = null,
        ?string $sortBy = 'name',
        ?SortDirection $sortDirection = SortDirection::ASC
    ): LengthAwarePaginator {

        if (! in_array($sortBy, ['name', 'date'])) {
            $sortBy = 'name';
        }

        return $project->meetings()
            ->when($search, function ($query, $search) {
                $query->whereLike('meetings.name', "%$search%")
                    ->orWhereLike('meetings.description', "%$search%");
            })
            ->when($dateStart, function ($query, $dateStart) {
                return $query->whereDate('meetings.date', '>=', $dateStart);
            })
            ->when($dateEnd, function ($query, $dateEnd) {
                return $query->whereDate('meetings.date', '<=', $dateEnd);
            })
            ->orderBy($sortBy, $sortDirection->value)
            ->paginate(config('app.pagination_items'));
    }

    public function lastMeeings(
        Project $project,
        int $take = 5
    ) {

        return $project->meetings()
            ->orderBy('date', SortDirection::DESC->value)
            ->take($take)
            ->get();
    }

    /**
     * MeetingService Construct.
     *
     * @param CreateMeeting $createMeeting
     *
     * @return MeetingService
     */
    public function __construct(protected CreateMeeting $createMeeting, protected UpdateMeeting $updateMeeting, protected DeleteMeeting $deleteMeeting) {}

    /**
     * Creates a new meeting.
     *
     * @param User             $user,
     * @param CreateMeetingDTO $createMeetingDTO,
     *
     * @return Meeting
     */
    public function create(
        User $user,
        CreateMeetingDTO $createMeetingDTO
    ): Meeting {
        return ($this->createMeeting)($createMeetingDTO);
    }

    /**
     * Update an existing meeting.
     *
     * @param UpdateMeetingDTO $updateMeetingDTO
     *
     * @return Meeting
     */
    public function update(
        User $user,
        UpdateMeetingDTO $updateMeetingDTO
    ): Meeting {
        return ($this->updateMeeting)(
            $updateMeetingDTO
        );
    }

    /**
     * Delete a new meeting.
     *
     * @param User             $user,
     * @param DeleteMeetingDTO $deleteMeetingDTO,
     *
     * @return void
     */
    public function delete(
        User $user,
        DeleteMeetingDTO $deleteMeetingDTO
    ): void {
        ($this->deleteMeeting)($deleteMeetingDTO);
    }
}
