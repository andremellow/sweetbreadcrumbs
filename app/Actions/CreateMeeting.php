<?php

namespace App\Actions;

use App\Models\Meeting;
use App\Models\Project;
use Carbon\Carbon;

class CreateMeeting
{
    /**
     * Creates new meeting.
     *
     * @param Project       $project,
     * @param string        $name,
     * @param string | null $description,
     * @param Carbon | null $date,
     *
     * @return Meeting
     */
    public function __invoke(
        Project $project,
        string $name,
        ?string $description,
        ?Carbon $date,
    ): Meeting {
        return $project->meetings()->create([
            'name' => $name,
            'description' => $description,
            'date' => $date,
        ]);

    }
}
