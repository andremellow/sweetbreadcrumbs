<?php

namespace App\Livewire\Workstream;

use App\DTO\Workstream\DeleteWorkstreamDTO;
use App\Livewire\Traits\WithSorting;
use App\Models\Workstream;
use App\Services\OrganizationService;
use App\Services\WorkstreamService;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[On(['workstream-created', 'workstream-updated'])]
class ListWorkstreams extends Component
{
    use WithPagination, WithSorting;

    #[Url()]
    public ?string $name = null;

    #[Url()]
    public ?int $priorityId = null;

    public bool $isFiltred = false;

    public function applyFilter() {}

    #[On('reset')]
    public function resetForm()
    {
        $this->reset('name', 'priorityId');
    }

    protected function list(OrganizationService $organizationService, WorkstreamService $workstreamService)
    {

        return $workstreamService->list(
            $organizationService->getOrganization(),
            $this->name,
            $this->priorityId,
            $this->sortBy,
            $this->sortDirection
        );
    }

    public function delete(WorkstreamService $workstreamService, int $workstreamId)
    {
        $workstreamService->delete(
            auth()->user(),
            new DeleteWorkstreamDTO(
                // THIS IS TERRIBLE
                workstream: Workstream::findOrFail($workstreamId)
            )
        );

        $this->dispatch('workstream-deleted', workstreamId: $workstreamId);
    }

    public function render(OrganizationService $organizationService, WorkstreamService $workstreamService)
    {
        $this->isFiltred = ! empty($this->name) || ! empty($this->priorityId);

        return view('livewire.workstream.list-workstreams', [
            'organization' => $organizationService->getOrganization(),
            'workstreams' => $this->list($organizationService, $workstreamService),
        ]);
    }
}
