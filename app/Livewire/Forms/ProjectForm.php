<?php

namespace App\Livewire\Forms;

use App\DTO\Project\CreateProjectDTO;
use App\DTO\Project\UpdateProjectDTO;
use App\Models\Project;
use Livewire\Attributes\Reactive;
use Livewire\Form;

class ProjectForm extends Form
{
    public ?int $id = null;

    public string $name = '';

    public ?int $priority_id = null;

    protected function rules()
    {
        return CreateProjectDTO::rules();
    }

    public function add($organizationService, $projectService)
    {
        $validated = $this->validate();

        return $projectService->create(
            auth()->user(),
            CreateProjectDTO::from([
                'organization' => $organizationService->getOrganization(),
                ...$validated
            ])
        );
    }

    public function edit($organizationService, $projectService)
    {
        $validated = $this->validate();

        $projectService->update(
            auth()->user(),
            UpdateProjectDTO::from([
                'organization' => $organizationService->getOrganization(),
                'project_id' => $this->id,
                ...$validated
            ])
        );

        $this->reset();
    }

    public function maybeLoadProject(?int $projectId)
    {
        if($projectId !== null) {
            $project = Project::findOrFail($projectId);
            $this->id = $project->id;
            $this->name = $project->name;
            $this->priority_id = $project->priority_id;
        } else {
            $this->reset();
        }
    }
}
