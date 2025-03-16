<?php

namespace App\Livewire\Traits;

use App\DTO\Task\CloseTaskDTO;
use App\Enums\SortDirection;
use App\Services\TaskService;
use Livewire\Attributes\Url;

trait CloseTask
{
    public function close(TaskService $taskService, $taskId)
    {
        $taskService->close(new CloseTaskDTO(
            user: auth()->user(),
            task_id: $taskId
        ));
        
        $this->dispatch('task-closed', taskId: $taskId);
    }
}
