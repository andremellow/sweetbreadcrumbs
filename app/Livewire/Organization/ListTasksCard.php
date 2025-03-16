<?php

namespace App\Livewire\Organization;

use App\Livewire\Traits\CloseTask;
use App\Services\OrganizationService;
use App\Services\TaskService;
use Livewire\Attributes\On;
use Livewire\Component;

#[On(['tasks-created', 'tasks-updated', 'tasks-completed'])]
class ListTasksCard extends Component
{
    use CloseTask;

    public function render(OrganizationService $organizationService, TaskService $taskService)
    {
        return view('livewire.organization.list-tasks-card', [
            'tasks' => $taskService->listForCard(
                taskable: $organizationService->getOrganization(),
                pageSize: 5
            )
        ]);
    }
}
