<?php

namespace App\Livewire\Project;

use App\Models\Project;
use App\Services\MeetingService;
use Livewire\Attributes\On;
use Livewire\Component;

#[On(['project-created', 'project-updated'])]
class Dashboard extends Component
{
    public Project $project;

    public function render()
    {
        return view('livewire.project.dashboard');
    }
}
