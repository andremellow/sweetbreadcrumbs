<?php

namespace App\Actions\Task;

use App\DTO\Task\CreateTaskDTO;
use App\Models\Task;
use Carbon\Carbon;

class CreateTask
{
    /**
     * Close a task.
     *
     * @param CreateTaskDTO $createTaskDTO,
     *
     * @return Task
     */
    public function __invoke(CreateTaskDTO $createTaskDTO
    ): Task {
        return $createTaskDTO->project->tasks()->create([
            'name' => $createTaskDTO->name,
            'description' => $createTaskDTO->description,
            'priority_id' => $createTaskDTO->priority_id,
            'due_date' => $createTaskDTO->due_date,
        ]);
    }
}
