<?php

namespace App\Livewire\Task;

use App\Enums\SortDirection;
use App\Livewire\Traits\WithSorting;
use App\Models\Workstream;
use App\Services\OrganizationService;
use App\Services\TaskService;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[On(['task-created', 'task-updated', 'task-deleted'])]
class ListTasks extends Component
{
    use WithPagination, WithSorting;

    #[Url()]
    public ?string $search = null;

    #[Url()]
    public ?string $status = 'open';

    #[Url()]
    public ?int $priorityId = null;

    #[Url()]
    public array $dateRange;

    #[Url(as: 'lates')]
    public bool $onlyLates = false;

    public bool $isFiltred = false;

    public Workstream $workstream;

    public function mount(Workstream $workstream)
    {
        $this->workstream = $workstream;
        $this->sortBy = 'due_date';
        $this->sortDirection = SortDirection::DESC;

    }

    public function updated($name)
    {
        if (in_array($name, ['status', 'dateRange'])) {
            $this->reset('onlyLates');
        }
    }

    public function toggleLate()
    {
        $this->onlyLates = ! $this->onlyLates;
    }

    public function applyFilter() {}

    #[On('reset')]
    public function resetForm()
    {
        $this->reset(['search', 'status', 'dateRange', 'priorityId', 'onlyLates']);
    }

    protected function list(TaskService $taskService)
    {
        if ($this->onlyLates) {
            $this->reset(['status', 'dateRange']);
        }
        $endDate = isset($this->dateRange) && array_key_exists('end', $this->dateRange)
            ? Carbon::parse($this->dateRange['end']) : null;

        $startDate = isset($this->dateRange) && array_key_exists('start', $this->dateRange)
                ? Carbon::parse($this->dateRange['start']) : null;

        return $taskService->list(
            taskable: $this->workstream,
            search: $this->search,
            priorityId: $this->priorityId,
            status: $this->onlyLates ? 'open' : $this->status,
            dateStart: $startDate,
            dateEnd: $this->onlyLates ? Carbon::now() : $endDate,
            sortBy: $this->sortBy,
            sortDirection: $this->sortDirection
        );
    }

    protected function isFiltered()
    {
        return
            ! empty($this->search)
            || ! empty($this->status)
            || ! empty($this->priorityId)
            || isset($this->dateRange)
            || $this->onlyLates === true;
    }

    public function render(OrganizationService $organizationService, TaskService $taskService)
    {
        $this->isFiltred = $this->isFiltered();

        return view('livewire.task.list-tasks', [
            'workstream' => $this->workstream,
            'organization' => $organizationService->getOrganization(),
            'tasks' => $this->list($taskService),
        ]);
    }
}
