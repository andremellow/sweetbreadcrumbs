<?php

namespace App\Livewire\Forms;

use App\DTO\Task\CreateTaskDTO;
use App\DTO\Task\UpdateTaskDTO;
use App\Models\Task;
use App\Models\Workstream;
use App\Services\TaskService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Form;

class TaskForm extends Form
{
    public ?int $id = null;

    public string $name = '';

    public string $description = '';

    public ?int $priority_id = null;

    public ?Carbon $due_date;

    public Workstream $workstream;

    public int $defaultPriorityId;

    protected function rules(): array
    {
        return CreateTaskDTO::rules();
    }

    public function add(TaskService $taskService): Task
    {
        $validated = $this->validate();

        return $taskService->create(
            CreateTaskDTO::from([
                'user' => Auth::user(),
                'taskable' => $this->workstream,
                ...$validated,
            ])
        );

    }

    public function edit(TaskService $taskService): task
    {
        $validated = $this->validate();

        return $taskService->update(
            UpdateTaskDTO::from([
                'user' => Auth::user(),
                'task_id' => $this->id,
                ...$validated,
            ])
        );
    }

    public function maybeLoadTask(?int $taskId): void
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

    public function resetForm(): void
    {
        $this->reset('id', 'name', 'description', 'due_date');
        $this->priority_id = $this->defaultPriorityId;
    }
}
