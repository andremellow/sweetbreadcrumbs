<?php

namespace App\Livewire\Meeting;

use App\Enums\EventEnum;
use App\Livewire\Forms\MeetingForm;
use App\Models\Workstream;
use App\Services\MeetingService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class MeetingModal extends Component
{
    public MeetingForm $form;

    public bool $showMeetingFormModal = false;

    public function mount(Workstream $workstream): void
    {
        $this->form->workstream = $workstream;
    }

    #[On([EventEnum::LOAD_MEETING_FORM_MODAL->value])]
    public function load(?int $meetingId = null): void
    {
        $this->form->maybeLoadMeeting($meetingId);

        $this->showMeetingFormModal = true;
    }

    public function onModalClose(): void
    {
        $this->form->reset('id', 'name', 'description', 'date');
    }

    public function save(MeetingService $meetingService): void
    {
        if ($this->form->id === null) {
            $meeting = $this->form->add($meetingService);
            $this->dispatch(EventEnum::MEETING_CREATED->value, meetingId: $meeting->id);
        } else {
            $this->form->edit($meetingService);
            $this->dispatch(EventEnum::MEETING_UPDATED->value, meetingId: $this->form->id);
        }

        $this->form->reset('id', 'name', 'description', 'date');
        $this->showMeetingFormModal = false;
    }

    public function render(): View
    {
        return view('livewire.meeting.meeting-modal');
    }
}
