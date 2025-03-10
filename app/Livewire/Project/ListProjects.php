<?php

namespace App\Livewire\Project;

use App\DTO\Project\DeleteProjectDTO;
use App\Livewire\Traits\WithSorting;
use App\Models\Organization;
use App\Models\Project;
use App\Services\OrganizationService;
use App\Services\ProjectService;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[On('project-created')] 
class ListProjects extends Component
{
    use WithPagination, WithSorting;

    #[Url()]
    public ?string $name = null;

    #[Url()]
    public ?int $priorityId = null;

    public bool $isFiltred = false;

    public function mount()
    {
        $this->sortBy = 'name';
    }

    #[On('onPriorityDropdownSelected')]
    public function updatePriorityId($priorityId)
    {
        $this->priorityId = $priorityId;
    }

    public function applyFilter() {}

    #[On('reset')]
    public function resetForm()
    {
        $this->reset('name', 'priorityId');
    }

    protected function list(OrganizationService $organizationService, ProjectService $projectService)
    {
        return $projectService->list(
            $organizationService->getOrganization(),
            $this->name,
            $this->priorityId,
            $this->sortBy,
            $this->sortDirection
        );
    }

    public function delete(ProjectService $projectService, int $projectId)
    {
        $projectService->delete(
            auth()->user(),
            new DeleteProjectDTO(
                //THIS IS TERRIBLE
                project: Project::findOrFail($projectId)
            )
        );
        
        $this->dispatch('project-deleted', projectId: $projectId);
    }

    public function render(OrganizationService $organizationService, ProjectService $projectService)
    {
        $this->isFiltred = ! empty($this->name) || ! empty($this->priorityId);

        return view('livewire.project.list-projects', [
            'projects' => $this->list($organizationService, $projectService),
        ]);
    }
}
