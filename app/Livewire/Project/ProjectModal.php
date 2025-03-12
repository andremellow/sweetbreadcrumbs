<?php

namespace App\Livewire\Project;

use App\Livewire\Forms\ProjectForm;
use App\Models\Organization;
use App\Services\OrganizationService;
use App\Services\ProjectService;
use Livewire\Attributes\On;
use Livewire\Component;

class ProjectModal extends Component
{
    public ProjectForm $form;

    public $showProjectFormModal = false;

    public ?Organization $organization;

    #[On(['load-project-form-modal'])]
    public function load(?int $projectId = null)
    {
        $this->form->maybeLoadProject($projectId);

        $this->showProjectFormModal = true;
    }

    public function onModalClose()
    {
        $this->form->reset();
    }

    public function save(OrganizationService $organizationService, ProjectService $projectService)
    {
        if ($this->form->id === null) {
            $project = $this->form->add($organizationService, $projectService);
            $this->dispatch('project-created', projectId: $project->id);
        } else {
            $this->form->edit($organizationService, $projectService);
            $this->dispatch('project-updated', projectId: $this->form->id);
        }

        $this->reset();
        $this->showProjectFormModal = false;
    }

    public function render(OrganizationService $organizationService)
    {
        return view('livewire.project.project-modal', [
            'organization' => $organizationService->getOrganization(),
        ]);
    }
}
