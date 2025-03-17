<?php

namespace App\Livewire\Traits;

use App\DTO\Task\CloseTaskDTO;
use App\Enums\EventEnum;
use App\Services\TaskService;

trait CloseTask
{
    public function close(TaskService $taskService, $taskId)
    {
        $taskService->close(new CloseTaskDTO(
            user: auth()->user(),
            task_id: $taskId
        ));

        if (isset($this->task)) {
            $this->task->refresh();
        }

        $this->dispatch(EventEnum::TASK_CLOSED, taskId: $taskId);
    }
}
