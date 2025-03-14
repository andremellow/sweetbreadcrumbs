<?php

namespace App\Livewire\Forms;

use App\DTO\Task\CreateTaskDTO;
// use App\DTO\Task\UpdateTaskDTO;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;
use Carbon\Carbon;
use Livewire\Form;

class TaskForm extends Form
{
    public ?int $id = null;

    public string $name = '';

    public string $description = '';

    public ?int $priority_id = null;

    public ?Carbon $due_date;

    public Project $project;

    public int $defaultPriorityId;

    protected function rules()
    {
        return CreateTaskDTO::rules();
    }

    public function add(TaskService $taskService)
    {
        $validated = $this->validate();

        if ($validated['priority_id']) {

            return $taskService->create(
                CreateTaskDTO::from([
                    'user' => auth()->user(),
                    'project' => $this->project,
                    ...$validated,
                ])
            );
        }
    }

    // public function edit(TaskService $taskService)
    // {
    //     $validated = $this->validate();

    //     $taskService->update(
    //         auth()->user(),
    //         UpdateTaskDTO::from([
    //             'project' => $this->project,
    //             'task_id' => $this->id,
    //             ...$validated,
    //         ])
    //     );
    // }

    public function maybeLoadTask(?int $taskId)
    {
        if ($taskId !== null) {
            $task = Task::findOrFail($taskId);
            $this->id = $task->id;
            $this->name = $task->name;
            $this->description = $task->description;
            $this->priority_id = $task->priority_id;
            $this->due_date = $task->due_date;
        } else {
            $this->resetForm();
        }
    }

    public function resetForm()
    {
        $this->reset('id', 'name', 'description', 'due_date');
        $this->priority_id = $this->defaultPriorityId;
    }
}
