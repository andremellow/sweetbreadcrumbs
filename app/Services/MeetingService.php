<?php

namespace App\Services;

use App\Actions\CreateMeeting;
use App\Actions\UpdateMeeting;
use App\Actions\UpdateProject;
use App\Enums\SortDirection;
use App\Models\Meeting;
use App\Models\Organization;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MeetingService
{
    /**
     * MeetingService Construct
     *
     * @param CreateMeeting $createMeeting
     * @return MeetingService
     */
    public function __construct(protected CreateMeeting $createMeeting, protected UpdateMeeting $updateMeeting)
    {

    }

    /**
     * Creates a new meeting
     *
    * @param Project $project,
     * @param string $name,
     * @param string | null $description,
     * @param String | Carbon $date,
     * @return Meeting
     */
    public function create(
        project $project,
        string $name,
        string | null $description,
        Carbon | string | null $date,
    ): Meeting
    {

        return ($this->createMeeting)(
            $project,
            $name,
            $description,
            $this->maybeParseToCarbon($date)
        );
    }

    /**
     * Update an existing meeting
     *
     * @param Project $project,
     * @param string $name,
     * @param string | null $description,
     * @param String | Carbon $date,
     * @return Meeting
     */
    public function update(
        project $project,
        int $meetingId,
        string $name,
        string | null $description,
        Carbon | string | null $date,
    ): Meeting
    {
       return ($this->updateMeeting)(
            $project,
            $meetingId,
            $name,
            $description,
            $this->maybeParseToCarbon($date)
        );
    }

    protected function maybeParseToCarbon(String | Carbon | null $maybeADate): Carbon | null
    {
        if(!($maybeADate instanceof Carbon) && !is_null($maybeADate)) {
            return Carbon::parse($maybeADate);
        }

        return $maybeADate;
    }

    public function list(
        Project $project,
        String | null  $name,
        String $sortBy = 'name',
        SortDirection $sortDirection = SortDirection::ASC
    ): LengthAwarePaginator
    {
        return $project->meetings()
                ->when($name, function($query, $name){
                    return $query->where('meetings.name', 'like', "%$name%");
                })
                ->orderBy($sortBy, $sortDirection->value)
                ->paginate(config('app.pagination_items'))
                ->appends([
                    'sort_by' => $sortBy,
                    'sort_direction' => $sortDirection->value,
                    'name' => $name
                ]);
    }

}
