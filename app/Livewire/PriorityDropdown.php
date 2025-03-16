<?php

namespace App\Livewire;

use App\Models\Organization;
use App\Services\OrganizationService;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class PriorityDropdown extends Component
{
    #[Modelable]
    public ?int $priorityId;
    public Organization $organization;
    public function mount(OrganizationService $organizationService)
    {
        $this->organization = $organizationService->getOrganization();
    }
    

    public function render(OrganizationService $organizationService)
    {
        $organizationService->setOrganization($this->organization);

        return view('livewire.priority-dropdown', [
            'priorities' => $organizationService->getPrioritiesDropDownData(),
        ]);
    }
}
