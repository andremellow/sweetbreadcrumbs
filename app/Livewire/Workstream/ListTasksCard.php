<?php

namespace App\Livewire\Workstream;

use App\Livewire\Traits\CloseTask;
use App\Models\Workstream;
use App\Services\MeetingService;
use App\Services\TaskService;
use Livewire\Attributes\On;
use Livewire\Component;

#[On(['tasks-created', 'tasks-updated', 'tasks-completed'])]
class ListTasksCard extends Component
{
    use CloseTask;

    public Workstream $workstream;

    public function render(TaskService $taskService)
    {
        return view('livewire.workstream.list-tasks-card', [
            'tasks' => $taskService->listForCard(
                taskable: $this->workstream,
                pageSize: 5
            )
        ]);
    }
}
