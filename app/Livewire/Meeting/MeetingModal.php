<?php

namespace App\Livewire\Meeting;

use App\Livewire\Forms\MeetingForm;
use App\Models\Project;
use App\Services\MeetingService;
use Illuminate\Http\Request;
use Livewire\Attributes\On;
use Livewire\Component;

class MeetingModal extends Component
{
    public MeetingForm $form;

    public $showMeetingFormModal = false;

    public function mount(Project $project)
    {
        $this->form->project = $project;
    }

    #[On(['load-meeting-form-modal'])]
    public function load(?int $meetingId = null)
    {
        $this->form->maybeLoadMeeting($meetingId);

        $this->showMeetingFormModal = true;
    }

    public function onModalClose(Request $request)
    {
        $this->form->reset('id', 'name', 'description', 'date');
    }

    public function save(MeetingService $meetingService)
    {
        if ($this->form->id === null) {
            $meeting = $this->form->add($meetingService);
            $this->dispatch('meeting-created', meetingId: $meeting->id);
        } else {
            $this->form->edit($meetingService);
            $this->dispatch('meeting-updated', meetingId: $this->form->id);
        }

        $this->form->reset('id', 'name', 'description', 'date');
        $this->showMeetingFormModal = false;
    }

    public function render()
    {
        return view('livewire.meeting.meeting-modal');
    }
}
