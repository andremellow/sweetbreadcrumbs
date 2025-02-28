<?php

namespace App\Actions;

use App\Models\Meeting;
use App\Models\Project;

use Carbon\Carbon;


class UpdateMeeting
{

    /**
     * Creates new meeting
     *
     * @param Project $project,
     * @param string $name,
     * @param string | null $description,
     * @param Carbon | null $date,
     * @return Meeting
     */
    public function __invoke(
        Project $project,
        int $meetingId,
        string $name,
        string | null $description,
        Carbon | null $date,
    ): Meeting
    {
        $meeting =  $project->meetings()->findOrFail($meetingId);
        
        $meeting->update([
            "name" => $name,
            "description" => $description,
            "date" => $date
        ]);

        return $meeting;

    }
}
