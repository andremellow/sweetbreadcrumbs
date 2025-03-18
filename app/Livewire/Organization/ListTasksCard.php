<?php

namespace App\Livewire\Organization;

use App\Enums\EventEnum;
use App\Livewire\Traits\CloseTask;
use App\Services\OrganizationService;
use App\Services\TaskService;
use Livewire\Attributes\On;
use Livewire\Component;

#[On([EventEnum::TASK_CREATED->value, EventEnum::TASK_DELETED->value, EventEnum::TASK_CLOSED->value])]
class ListTasksCard extends Component
{
    use CloseTask;

    public function render(OrganizationService $organizationService, TaskService $taskService)
    {
        return view('livewire.organization.list-tasks-card', [
            'tasks' => $taskService->listForCard(
                taskable: $organizationService->getOrganization(),
                pageSize: 5
            ),
        ]);
    }
}
