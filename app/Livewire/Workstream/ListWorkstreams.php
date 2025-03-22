<?php

namespace App\Livewire\Workstream;

use App\DTO\Workstream\DeleteWorkstreamDTO;
use App\Enums\EventEnum;
use App\Livewire\Traits\WithSorting;
use App\Models\Workstream;
use App\Services\UserService;
use App\Services\WorkstreamService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[On([EventEnum::WORKSTREAM_CREATED->value, EventEnum::WORKSTREAM_UPDATED->value])]
class ListWorkstreams extends Component
{
    use WithPagination, WithSorting;

    #[Url()]
    public ?string $name = null;

    #[Url()]
    public ?int $priorityId = null;

    public bool $isFiltred = false;

    public function applyFilter(): void {}

    #[On(EventEnum::RESET->value)]
    public function resetForm(): void
    {
        $this->reset('name', 'priorityId');
    }

    protected function list(UserService $userService, WorkstreamService $workstreamService): LengthAwarePaginator
    {

        return $workstreamService->list(
            $userService->getCurrentOrganization(),
            $this->name,
            $this->priorityId,
            $this->sortBy,
            $this->sortDirection
        );
    }

    public function delete(WorkstreamService $workstreamService, int $workstreamId): void
    {
        $workstreamService->delete(
            Auth::user(),
            new DeleteWorkstreamDTO(
                // THIS IS TERRIBLE
                workstream: Workstream::findOrFail($workstreamId)
            )
        );

        $this->dispatch(EventEnum::WORKSTREAM_DELETED->value, workstreamId: $workstreamId);
    }

    public function render(UserService $userService, WorkstreamService $workstreamService): View
    {
        $this->isFiltred = ! empty($this->name) || ! empty($this->priorityId);

        return view('livewire.workstream.list-workstreams', [
            'organization' => $userService->getCurrentOrganization(),
            'workstreams' => $this->list($userService, $workstreamService),
        ]);
    }
}
