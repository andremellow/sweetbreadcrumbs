<?php

namespace App\Livewire\Forms;

use App\DTO\Meeting\CreateMeetingDTO;
use App\DTO\Meeting\UpdateMeetingDTO;
use App\Models\Meeting;
use App\Models\Workstream;
use App\Services\MeetingService;
use Carbon\Carbon;
use Livewire\Form;

class MeetingForm extends Form
{
    public ?int $id = null;

    public string $name = '';

    public string $description = '';

    public ?Carbon $date;

    public Workstream $workstream;

    protected function rules()
    {
        return CreateMeetingDTO::rules();
    }

    public function add(MeetingService $meetingService)
    {
        $validated = $this->validate();

        return $meetingService->create(
            auth()->user(),
            CreateMeetingDTO::from([
                'workstream' => $this->workstream,
                ...$validated,
            ])
        );
    }

    public function edit(MeetingService $meetingService)
    {
        $validated = $this->validate();

        $meetingService->update(
            auth()->user(),
            UpdateMeetingDTO::from([
                'workstream' => $this->workstream,
                'meeting_id' => $this->id,
                ...$validated,
            ])
        );
    }

    public function maybeLoadMeeting(?int $meetingId)
    {
        if ($meetingId !== null) {
            $meeting = Meeting::findOrFail($meetingId);
            $this->id = $meeting->id;
            $this->name = $meeting->name;
            $this->description = $meeting->description;
            $this->date = $meeting->date;
        } else {
            $this->reset('id', 'name', 'description', 'date');
        }
    }
}
