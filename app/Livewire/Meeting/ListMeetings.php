<?php

namespace App\Livewire\Meeting;

use App\DTO\Meeting\DeleteMeetingDTO;
use App\Livewire\Traits\WithSorting;
use App\Models\Meeting;
use App\Models\Project;
use App\Services\MeetingService;
use App\Services\OrganizationService;
use Flux\DateRange;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListMeetings extends Component
{
    use WithPagination, WithSorting;

    #[Url()]
    public ?string $search = null;

    // #[Url()]
    public DateRange $dateRange;

    public bool $isFiltred = false;

    public Project $project;

    public function mount(Project $project)
    {
        $this->project = $project;
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
            $this->project,
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
            'meetings' => $this->list($organizationService, $meetingService),
        ]);
    }
}
