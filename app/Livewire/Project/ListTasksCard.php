<?php

namespace App\Livewire\Project;

use App\Livewire\Traits\CloseTask;
use App\Models\Project;
use App\Services\MeetingService;
use App\Services\TaskService;
use Livewire\Attributes\On;
use Livewire\Component;

#[On(['tasks-created', 'tasks-updated', 'tasks-completed'])]
class ListTasksCard extends Component
{
    use CloseTask;

    public Project $project;

    public function render(TaskService $taskService)
    {
        return view('livewire.project.list-tasks-card', [
            'tasks' => $taskService->listForCard(
                taskable: $this->project,
                pageSize: 5
            )
        ]);
    }
}
