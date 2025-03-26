<?php

namespace App\Livewire\Organization;

use App\Enums\EventEnum;
use App\Livewire\Traits\CloseTask;
use App\Services\TaskService;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

#[On([EventEnum::TASK_CREATED->value, EventEnum::TASK_DELETED->value, EventEnum::TASK_CLOSED->value])]
class ListTasksCard extends Component
{
    use CloseTask;

    public function render(UserService $userService, TaskService $taskService): View
    {
        return view('livewire.organization.list-tasks-card', [
            'tasks' => $taskService->listForCard(
                taskable: $userService->getCurrentOrganization(),
                pageSize: 5
            ),
        ]);
    }
}
