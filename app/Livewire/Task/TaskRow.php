<?php

namespace App\Livewire\Task;

use App\DTO\Task\DeleteTaskDTO;
use App\Enums\EventEnum;
use App\Livewire\Traits\CloseTask;
use App\Livewire\Traits\OpenTask;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class TaskRow extends Component
{
    use CloseTask, OpenTask;

    public Task $task;

    #[On(EventEnum::TASK_UPDATED->value.'.{task.id}')]
    public function refreshTask(): void
    {
        $this->task->refresh();
    }

    public function delete(TaskService $taskService, int $taskId): void
    {
        $taskService->delete(
            new DeleteTaskDTO(
                user: Auth::user(),
                task_id: $taskId
            )
        );

        $this->dispatch(EventEnum::TASK_DELETED, taskId: $taskId);
        $this->dispatch(EventEnum::TASK_DELETED->value.".{$taskId}", taskId: $taskId);
    }

    public function render(): View
    {
        return view('livewire.task.task-row');
    }
}
