<?php

namespace App\Livewire\Organization;

use App\Enums\CapabilityEnum;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(UserService $userService): View
    {

        dd($userService->can(CapabilityEnum::MANAGE_ALL_MEETINGS));

        return view('livewire.organization.dashboard', [
            'organization' => $userService->getCurrentOrganization(),
        ]);
    }
}
