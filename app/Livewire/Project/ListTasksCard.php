<?php

namespace App\Livewire\Project;

use App\DTO\Task\CloseTaskDTO;
use App\Models\Project;
use App\Services\MeetingService;
use App\Services\TaskService;
use Livewire\Attributes\On;
use Livewire\Component;

#[On(['tasks-created', 'tasks-updated', 'tasks-completed'])]
class ListTasksCard extends Component
{
    public Project $project;

    public function close($taskId)
    {
        
        $taskService = app(TaskService::class);
        $taskService->close(new CloseTaskDTO(
            user: auth()->user(),
            task_id: $taskId
        ));
        
        $this->dispatch('task-closed', taskId: $taskId);
    }

    public function render(TaskService $taskService)
    {
        return view('livewire.project.list-tasks-card', [
            'tasks' => $taskService->listForCard(
                project: $this->project,
                pageSize: 5
            )
        ]);
    }
}
