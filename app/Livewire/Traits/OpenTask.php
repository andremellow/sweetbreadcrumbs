<?php

namespace App\Livewire\Traits;

use App\DTO\Task\OpenTaskDTO;
use App\Enums\EventEnum;
use App\Services\TaskService;

trait OpenTask
{
    public function open(TaskService $taskService, int $taskId)
    {
        $taskService->open(new OpenTaskDTO(
            user: auth()->user(),
            task_id: $taskId
        ));

        $this->task->refresh();

        $this->dispatch(EventEnum::TASK_OPENED, taskId: $taskId);

    }
}
