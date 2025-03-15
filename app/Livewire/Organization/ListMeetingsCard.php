<?php

namespace App\Livewire\Organization;

use App\Models\Project;
use App\Services\MeetingService;
use App\Services\OrganizationService;
use Livewire\Attributes\On;
use Livewire\Component;

#[On(['meeting-created', 'meeting-updated'])]
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
