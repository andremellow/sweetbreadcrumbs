<?php

namespace App\Livewire;

use App\Services\OrganizationService;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class RoleDropdown extends Component
{
    #[Modelable]
    public ?int $roleId;

    public function render(UserService $userService, OrganizationService $organizationService): View
    {
        $organizationService->setOrganization($userService->getCurrentOrganization());

        return view('livewire.role-dropdown', [
            'roles' => $organizationService->getRolesDropDownData(),
        ]);
    }
}
