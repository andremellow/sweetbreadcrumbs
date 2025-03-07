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
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MeetingService
{
    public function list(
        Project $project,
        ?string $name = null,
        ?string $sortBy = 'name',
        ?SortDirection $sortDirection = SortDirection::ASC
    ): LengthAwarePaginator {

        return $project->meetings()
            ->when($name, function ($query, $name) {
                return $query->where('meetings.name', 'like', "%$name%");
            })
            ->orderBy($sortBy, $sortDirection->value)
            ->paginate(config('app.pagination_items'))
            ->appends([
                'sort_by' => $sortBy,
                'sort_direction' => $sortDirection->value,
                'name' => $name,
            ]);
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
