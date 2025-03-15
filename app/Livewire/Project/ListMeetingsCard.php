<?php

namespace App\Livewire\Project;

use App\Models\Project;
use App\Services\MeetingService;
use Livewire\Attributes\On;
use Livewire\Component;

#[On(['meeting-created', 'meeting-updated'])]
class ListMeetingsCard extends Component
{
    public Project $project;

    public function render(MeetingService $meetingService)
    {
        return view('livewire.project.list-meetings-card', [
            'meetings' => $meetingService->lastMeeings($this->project, 5),
        ]);
    }
}
