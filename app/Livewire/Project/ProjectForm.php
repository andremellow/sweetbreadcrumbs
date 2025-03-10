<?php

namespace App\Livewire\Project;

use App\DTO\Project\CreateProjectDTO;
use App\Models\Organization;
use App\Services\OrganizationService;
use App\Services\ProjectService;
use Livewire\Attributes\On;
use Livewire\Component;

class CreateProject extends Component
{
    public $showCreateProjectModal = false;

    public string $name = '';

    public ?int $priority_id = null;

    protected function rules()
    {
        return CreateProjectDTO::rules();
    }

    #[On('onPriorityDropdownSelectedForCreateProject')]
    public function updatePriorityId($priorityId)
    {
        $this->priority_id = $priorityId;
    }

    public function add(OrganizationService $organizationService, ProjectService $projectService)
    {
        $validated = $this->validate();

        $project = $projectService->create(
            auth()->user(),
            CreateProjectDTO::from([
                'organization' => $organizationService->getOrganization(),
                ...$validated
            ])
        );

        $this->showCreateProjectModal = false;

        $this->dispatch('project-created', projectId: $project);
    }

    public function render()
    {
        return view('livewire.project.create-project');
    }
}
