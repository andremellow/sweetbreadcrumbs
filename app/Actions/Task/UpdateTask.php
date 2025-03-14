<?php

namespace App\Actions\Task;

use App\DTO\Task\UpdateTaskDTO;
use App\Models\Task;

class UpdateTask
{
    /**
     * Close a task.
     *
     * @param UpdateTaskDTO $updateTaskDTO,
     *
     * @return Task
     */
    public function __invoke(UpdateTaskDTO $updateTaskDTO
    ): Task {
        $task = Task::findOrFail($updateTaskDTO->task_id);
        
        $task->update([
            'name' => $updateTaskDTO->name,
            'description' => $updateTaskDTO->description,
            'priority_id' => $updateTaskDTO->priority_id,
            'due_date' => $updateTaskDTO->due_date,
        ]);

        return $task;
    }
}
