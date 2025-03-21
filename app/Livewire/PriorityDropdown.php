<?php

namespace App\Livewire;

use App\Services\OrganizationService;
use App\Services\UserService;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class PriorityDropdown extends Component
{
    #[Modelable]
    public ?int $priorityId;

    public function render(UserService $userService, OrganizationService $organizationService)
    {
        $organizationService->setOrganization($userService->getCurrentOrganization());

        return view('livewire.priority-dropdown', [
            'priorities' => $organizationService->getPrioritiesDropDownData(),
        ]);
    }
}
