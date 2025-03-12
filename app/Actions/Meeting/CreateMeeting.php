<?php

namespace App\Actions\Meeting;

use App\DTO\Meeting\CreateMeetingDTO;
use App\Models\Meeting;

class CreateMeeting
{
    /**
     * Creates new meeting.
     *
     * @param CreateMeetingDTO $createMeetingDTO,
     *
     * @return Meeting
     */
    public function __invoke(CreateMeetingDTO $createMeetingDTO
    ): Meeting {
        return $createMeetingDTO->project->meetings()->create([
            'name' => $createMeetingDTO->name,
            'description' => $createMeetingDTO->description,
            'date' => $createMeetingDTO->date,
        ]);
    }
}
