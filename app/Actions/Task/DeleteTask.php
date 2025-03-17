<?php

namespace App\Actions\Task;

use App\DTO\Task\DeleteTaskDTO;
use App\Models\Task;

class DeleteTask
{
    /**
     * Close a task.
     *
     * @param DeleteTaskDTO $updateTaskDTO,
     *
     * @return void
     */
    public function __invoke(DeleteTaskDTO $updateTaskDTO
    ): void {
        Task::find($updateTaskDTO->task_id)->delete();
    }
}
