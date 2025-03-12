<?php

namespace App\Livewire\Task;

use App\Enums\SortDirection;
use App\Livewire\Traits\WithSorting;
use App\Models\Meeting;
use App\Models\Project;
use App\Services\OrganizationService;
use App\Services\TaskService;
use Flux\DateRange;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[On(['task-created', 'task-updated'])]
class ListTasks extends Component
{
    use WithPagination, WithSorting;

    #[Url()]
    public ?string $search = null;

    #[Url()]
    public ?string $status = null;

    #[Url()]
    public ?int $priorityId = null;

    #[Url()]
    public DateRange $dateRange;

    public bool $isFiltred = false;

    public Project $project;

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->sortBy = 'due_date';
    }

    public function applyFilter() {}

    #[On('reset')]
    public function resetForm()
    {
        $this->reset('search', 'dateRange');
    }

        public function open(TaskService $taskService, int $taskId)
        {
            $taskService->open(
                auth()->user(),
                $taskId
            );

            $this->dispatch('task-opened', taskId: $taskId);
        }

        public function close(TaskService $taskService, int $taskId)
        {
            $taskService->close(
                auth()->user(),
                $taskId
            );

            $this->dispatch('task-closed', taskId: $taskId);
        }


    // public function delete(TaskService $taskService, int $taskId)
    // {
    //     $taskService->delete(
    //         auth()->user(),
    //         new DeleteTaskDTO(
    //             meeting: Task::findOrFail($taskId)
    //         )
    //     );

    //     $this->dispatch('task-deleted', taskId: $taskId);
    // }

    protected function list(TaskService $taskService)
    {
        return $taskService->list(
            $this->project,
            $this->search,
            $this->priorityId,
            $this->status,
            isset($this->dateRange) ? $this->dateRange->start() : null,
            isset($this->dateRange) ? $this->dateRange->end() : null,
            $this->sortBy,
            $this->sortDirection
        );
    }

    public function render(OrganizationService $organizationService, TaskService $taskService)
    {
        $this->isFiltred = ! empty($this->search) || isset($this->dateRange);

        return view('livewire.task.list-tasks', [
            'project' => $this->project,
            'organization' => $organizationService->getOrganization(),
            'tasks' => $this->list($taskService),
        ]);
    }
}
