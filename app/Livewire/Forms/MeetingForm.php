<?php

namespace App\Livewire\Forms;

use App\DTO\Meeting\CreateMeetingDTO;
use App\DTO\Meeting\UpdateMeetingDTO;
use App\Models\Meeting;
use App\Models\Project;
use App\Services\MeetingService;
use Carbon\Carbon;
use Livewire\Form;

class MeetingForm extends Form
{
    public ?int $id = null;

    public string $name = '';

    public string $description = '';

    public ?Carbon $date;

    public Project $project;

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
                'organization' => $this->project,
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
                'project' => $this->project,
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
