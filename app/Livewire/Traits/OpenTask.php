<?php

namespace App\Livewire\Traits;


use App\DTO\Task\OpenTaskDTO;
use App\Services\TaskService;

trait OpenTask
{
    public function open(TaskService $taskService)
    {
        $taskService->open(new OpenTaskDTO(
            user: auth()->user(),
            task_id: $this->task->id
        ));

        $this->task->refresh();

        $this->dispatch('task-opened', taskId: $this->task->id);
    }
}
