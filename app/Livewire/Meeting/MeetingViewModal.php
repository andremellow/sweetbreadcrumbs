<?php

namespace App\Livewire\Meeting;

use App\Enums\EventEnum;
use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class MeetingViewModal extends Component
{
    public bool $showMeetingViewModal = false;

    public ?int $id = null;

    public ?string $name = null;

    public ?string $description = null;

    public ?Carbon $date = null;

    #[On([EventEnum::LOAD_MEETING_VIEW_MODAL->value])]
    public function load(?int $meetingId = null): void
    {
        $meeting = Meeting::findOrFail($meetingId);
        $this->id = $meeting->id;
        $this->name = $meeting->name;
        $this->description = $meeting->description;
        $this->date = $meeting->date;

        $this->showMeetingViewModal = true;
    }

    public function render(): View
    {
        return view('livewire.meeting.meeting-view-modal');
    }
}
