<?php

namespace App\Livewire\Meeting;

use App\DTO\Meeting\DeleteMeetingDTO;
use App\Livewire\Traits\WithSorting;
use App\Models\Meeting;
use App\Models\Workstream;
use App\Services\MeetingService;
use App\Services\OrganizationService;
use Flux\DateRange;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[On(['meeting-created', 'meeting-updated'])]
class ListMeetings extends Component
{
    use WithPagination, WithSorting;

    #[Url()]
    public ?string $search = null;

    #[Url()]
    public DateRange $dateRange;

    public bool $isFiltred = false;

    public Workstream $workstream;

    public function mount(Workstream $workstream)
    {
        $this->workstream = $workstream;
        $this->sortBy = 'name';
    }

    public function applyFilter() {}

    #[On('reset')]
    public function resetForm()
    {
        $this->reset('search', 'dateRange');
    }

    protected function list(OrganizationService $organizationService, MeetingService $meetingService)
    {
        return $meetingService->list(
            $this->workstream,
            $this->search,
            isset($this->dateRange) ? $this->dateRange->start() : null,
            isset($this->dateRange) ? $this->dateRange->end() : null,
            $this->sortBy,
            $this->sortDirection
        );
    }

    public function delete(MeetingService $meetingService, int $meetingId)
    {
        $meetingService->delete(
            auth()->user(),
            new DeleteMeetingDTO(
                meeting: Meeting::findOrFail($meetingId)
            )
        );

        $this->dispatch('meeting-deleted', meetingId: $meetingId);
    }

    public function render(OrganizationService $organizationService, MeetingService $meetingService)
    {
        $this->isFiltred = ! empty($this->search) || isset($this->dateRange);

        return view('livewire.meeting.list-meetings', [
            'workstream' => $this->workstream,
            'meetings' => $this->list($organizationService, $meetingService),
        ]);
    }
}
