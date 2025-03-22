<?php

namespace App\Livewire\Workstream;

use App\Enums\EventEnum;
use App\Livewire\Traits\CloseTask;
use App\Models\Workstream;
use App\Services\TaskService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

#[On([EventEnum::TASK_CREATED->value, EventEnum::TASK_DELETED->value, EventEnum::TASK_CLOSED->value])]
class ListTasksCard extends Component
{
    use CloseTask;

    public Workstream $workstream;

    public function render(TaskService $taskService): View
    {
        return view('livewire.workstream.list-tasks-card', [
            'tasks' => $taskService->listForCard(
                taskable: $this->workstream,
                pageSize: 5
            ),
        ]);
    }
}
