<?php

namespace App\Livewire\Task;

use App\Enums\EventEnum;
use App\Enums\SortDirection;
use App\Livewire\Traits\WithSorting;
use App\Models\Workstream;
use App\Services\TaskService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

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

    public function mount(Workstream $workstream): void
    {
        $this->workstream = $workstream;
        $this->sortBy = 'due_date';
        $this->sortDirection = SortDirection::DESC;

    }

    public function updated(string $name): void
    {
        if (in_array($name, ['status', 'dateRange'])) {
            $this->reset('onlyLates');
        }
    }

    public function toggleLate(): void
    {
        $this->onlyLates = ! $this->onlyLates;
    }

    #[On([EventEnum::TASK_CREATED->value, EventEnum::TASK_DELETED->value])]
    public function applyFilter(): void {}

    #[On([EventEnum::TASK_OPENED->value, EventEnum::TASK_CLOSED->value])]
    public function maybeReload(): void
    {
        if ($this->status === 'all') {
            $this->skipRender();
        }
    }

    #[On(EventEnum::RESET->value)]
    public function resetForm(): void
    {
        $this->reset(['search', 'status', 'dateRange', 'priorityId', 'onlyLates']);
    }

    protected function list(TaskService $taskService): LengthAwarePaginator
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
            sortDirection: $this->sortDirection,
        );
    }

    protected function isFiltered(): bool
    {
        return
            ! empty($this->search)
            || ! empty($this->status)
            || ! empty($this->priorityId)
            || isset($this->dateRange)
            || $this->onlyLates === true;
    }

    public function render(UserService $userService, TaskService $taskService): View
    {
        $this->isFiltred = $this->isFiltered();

        return view('livewire.task.list-tasks', [
            'workstream' => $this->workstream,
            'organization' => $userService->getCurrentOrganization(),
            'tasks' => $this->list($taskService),
        ]);
    }
}
