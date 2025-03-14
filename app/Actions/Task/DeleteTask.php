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
     * @return Void
     */
    public function __invoke(DeleteTaskDTO $updateTaskDTO
    ): Void {
        Task::find($updateTaskDTO->task_id)->delete();
    }
}
