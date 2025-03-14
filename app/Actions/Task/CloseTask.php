<?php

namespace App\Actions\Task;

use App\DTO\Task\CloseTaskDTO;
use App\Models\Task;
use Carbon\Carbon;

class CloseTask
{
    /**
     * Close a task.
     *
     * @param CloseTaskDTO $closeTaskDTO,
     *
     * @return Task
     */
    public function __invoke(CloseTaskDTO $closeTaskDTO
    ): Task {
        $task = Task::find($closeTaskDTO->task_id);

        $task->update([
            'completed_at' => Carbon::now(),
        ]);

        return $task;
    }
}
