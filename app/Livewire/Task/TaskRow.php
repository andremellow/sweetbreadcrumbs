<?php

namespace App\Livewire\Task;

use App\DTO\Task\CloseTaskDTO;
use App\DTO\Task\DeleteTaskDTO;
use App\DTO\Task\OpenTaskDTO;
use App\Models\Task;
use App\Services\TaskService;
use Livewire\Attributes\On;
use Livewire\Component;

class TaskRow extends Component
{
    public Task $task;

    #[On('task-updated.{task.id}')] 
    public function refreshTask()
    {
        $this->task->refresh();
    }
    public function open(TaskService $taskService)
    {
        $taskService->open(new OpenTaskDTO(
            user: auth()->user(),
            task_id: $this->task->id
        ));

        $this->task->refresh();

        $this->dispatch('task-opened', taskId: $this->task->id);
    }

    public function close(TaskService $taskService)
    {
        $taskService->close(new CloseTaskDTO(
            user: auth()->user(),
            task_id: $this->task->id
        ));

        $this->task->refresh();

        $this->dispatch('task-closed', taskId: $this->task->id);
    }

    public function delete(TaskService $taskService, int $taskId)
    {
        $taskService->delete(
            new DeleteTaskDTO(
                user: auth()->user(),
                task_id: $taskId
            )
        );

        $this->dispatch('task-deleted', taskId: $taskId);
        $this->dispatch("task-deleted.{$taskId}", taskId: $taskId);
    }

    public function render()
    {
        return view('livewire.task.task-row');
    }
}
