<?php

namespace App\Livewire\Project;

use App\Models\Project;
use App\Services\MeetingService;
use App\Services\OrganizationService;
use Livewire\Attributes\On;
use Livewire\Component;

#[On(['project-created', 'project-updated'])]
class Dashboard extends Component
{
    public Project $project;

    public function render(MeetingService $meetingService)
    {
        return view('livewire.project.dashboard', [
            'meetings' => $meetingService->lastMeeings($this->project, 5)
        ]);
    }
}
