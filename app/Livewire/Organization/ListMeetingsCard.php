<?php

namespace App\Livewire\Organization;

use App\Enums\EventEnum;
use App\Services\MeetingService;
use App\Services\OrganizationService;
use Livewire\Attributes\On;
use Livewire\Component;

#[On([EventEnum::MEETING_CREATED->value, EventEnum::MEETING_UPDATED->value])]
class ListMeetingsCard extends Component
{
    public function render(OrganizationService $organizaitonService, MeetingService $meetingService)
    {
        return view('livewire.organization.list-meetings-card', [
            'meetings' => $meetingService->lastMeeings(
                source: $organizaitonService->getOrganization(),
                take: 5
            ),
        ]);
    }
}
