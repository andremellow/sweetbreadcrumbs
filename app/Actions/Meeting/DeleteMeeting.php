<?php

namespace App\Actions\Meeting;

use App\DTO\Meeting\DeleteMeetingDTO;

class DeleteMeeting
{
    /**
     * Delete new meeting.
     *
     * @param DeleteMeetingDTO $deleteMeetingDTO,
     *
     * @return void
     */
    public function __invoke(DeleteMeetingDTO $deleteMeetingDTO
    ): void {
        $deleteMeetingDTO->meeting->delete();
    }
}
