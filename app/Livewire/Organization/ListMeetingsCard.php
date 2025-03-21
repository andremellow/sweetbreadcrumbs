<?php

namespace App\Livewire\Organization;

use App\Enums\EventEnum;
use App\Services\MeetingService;
use App\Services\UserService;
use Livewire\Attributes\On;
use Livewire\Component;

#[On([EventEnum::MEETING_CREATED->value, EventEnum::MEETING_UPDATED->value])]
class ListMeetingsCard extends Component
{
    public function render(UserService $userService, MeetingService $meetingService)
    {
        return view('livewire.organization.list-meetings-card', [
            'meetings' => $meetingService->lastMeeings(
                source: $userService->getCurrentOrganization(),
                take: 5
            ),
        ]);
    }
}
