<?php

namespace App\Livewire\Task;

use App\DTO\Task\DeleteTaskDTO;
use App\DTO\Task\OpenTaskDTO;
use App\Livewire\Traits\CloseTask;
use App\Livewire\Traits\OpenTask;
use App\Models\Task;
use App\Services\TaskService;
use Livewire\Attributes\On;
use Livewire\Component;

class TaskRow extends Component
{
    use CloseTask, OpenTask;

    public Task $task;

    #[On('task-updated.{task.id}')] 
    public function refreshTask()
    {
        $this->task->refresh();
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
