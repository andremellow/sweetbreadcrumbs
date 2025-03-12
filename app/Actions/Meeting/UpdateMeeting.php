<?php

namespace App\Actions\Meeting;

use App\DTO\Meeting\UpdateMeetingDTO;
use App\Models\Meeting;

class UpdateMeeting
{
    /**
     * Updates new meeting.
     *
     * @param UpdateMeetingDTO $updateMeetingDTO
     *
     * @return Meeting
     */
    public function __invoke(
        UpdateMeetingDTO $updateMeetingDTO
    ): Meeting {
        $meeting = $updateMeetingDTO->project->meetings()->findOrFail($updateMeetingDTO->meeting_id);

        $meeting->update([
            'name' => $updateMeetingDTO->name,
            'description' => $updateMeetingDTO->description,
            'date' => $updateMeetingDTO->date,
        ]);

        return $meeting;

    }
}
