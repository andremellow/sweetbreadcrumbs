<?php

namespace App\Livewire\Meeting;

use App\Livewire\Forms\MeetingForm;
use App\Models\Meeting;
use App\Models\Project;
use App\Services\MeetingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Livewire\Attributes\On;
use Livewire\Component;

class MeetingViewModal extends Component
{
    
    public $showMeetingViewModal = false;

    public ?int $id = null;

    public ?string $name = null;

    public ?string $description = null;

    public ?Carbon $date = null;


    #[On(['load-meeting-view-modal'])]
    public function load(?int $meetingId = null)
    {
        $meeting = Meeting::findOrFail($meetingId);
        $this->id = $meeting->id;
        $this->name = $meeting->name;
        $this->description = $meeting->description;
        $this->date = $meeting->date;

        $this->showMeetingViewModal = true;
    }

    public function render()
    {
        return view('livewire.meeting.meeting-view-modal');
    }
}
