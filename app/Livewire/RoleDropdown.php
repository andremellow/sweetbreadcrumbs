<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\OrganizationService;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class RoleDropdown extends Component
{
    #[Modelable]
    public ?int $roleId;

    #[Reactive]
    public ?User $user;

    public bool $withUser = false;

    #[Computed]
    public function roles(): Collection
    {
        $userService = app(UserService::class);
        $organizationService = app(OrganizationService::class);
        $organizationService->setOrganization($userService->getCurrentOrganization());

        return $this->withUser ?
                $organizationService->getRolesDropDownDataForUser($this->user) :
                $organizationService->getRolesDropDownData();
    }

    public function render(): View
    {

        return view('livewire.role-dropdown');
    }
}
