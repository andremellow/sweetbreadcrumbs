<?php

namespace App\Actions\Task;

use App\DTO\Task\OpenTaskDTO;
use App\Models\Task;

class OpenTask
{
    /**
     * Close a task.
     *
     * @param OpenTaskDTO $closeTaskDTO,
     *
     * @return Task
     */
    public function __invoke(OpenTaskDTO $openTaskDTO
    ): Task {
        $task = Task::find($openTaskDTO->task_id);

        $task->update([
            'completed_at' => null,
        ]);

        return $task;
    }
}
