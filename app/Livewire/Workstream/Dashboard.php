<?php

namespace App\Livewire\Workstream;

use App\Models\Workstream;
use App\Services\MeetingService;
use Livewire\Attributes\On;
use Livewire\Component;

#[On(['workstream-created', 'workstream-updated'])]
class Dashboard extends Component
{
    public Workstream $workstream;

    public function render()
    {
        return view('livewire.workstream.dashboard');
    }
}
