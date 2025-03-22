<?php

namespace App\Livewire\Workstream;

use App\Enums\EventEnum;
use App\Models\Workstream;
use App\Services\MeetingService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

#[On([EventEnum::MEETING_CREATED->value, EventEnum::MEETING_UPDATED->value])]
class ListMeetingsCard extends Component
{
    public Workstream $workstream;

    public function render(MeetingService $meetingService): View
    {
        return view('livewire.workstream.list-meetings-card', [
            'meetings' => $meetingService->lastMeeings($this->workstream, 5),
        ]);
    }
}
