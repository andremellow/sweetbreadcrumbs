<?php

namespace App\Livewire\Project;

use App\DTO\Project\CreateProjectDTO;
use App\Livewire\Forms\ProjectForm;
use App\Services\OrganizationService;
use App\Services\ProjectService;
use Livewire\Attributes\On;
use Livewire\Component;

class ProjectModal extends Component
{
    public ProjectForm $form;

    public $showProjectFormModal = false;

    #[On(['load-project-form-modal'])]
    public function load(?int $projectId = null)
    {
        $this->form->maybeLoadProject($projectId);

        $this->showProjectFormModal = true;
    }

    protected function rules()
    {
        return CreateProjectDTO::rules();
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

    public function render()
    {
        return view('livewire.project.project-modal');
    }
}
