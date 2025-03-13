<?php

namespace App\Livewire\Task;

use App\Models\Task;
use App\Services\TaskService;
use Livewire\Component;

class TaskRow extends Component
{
    public Task $task;

    public function open(TaskService $taskService)
    {
        $taskService->open(
            auth()->user(),
            $this->task->id
        );

        $this->task->refresh();

        $this->dispatch('task-opened', taskId: $this->task->id);
    }

    public function close(TaskService $taskService)
    {
        $taskService->close(
            auth()->user(),
            $this->task->id
        );

        $this->task->refresh();
        
        $this->dispatch('task-closed', taskId: $this->task->id);
    }

    public function render()
    {
        return view('livewire.task.task-row');
    }
}
