<?php

namespace App\Livewire\Organization;

use App\Services\UserService;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(UserService $userService)
    {
        return view('livewire.organization.dashboard', [
            'organization' => $userService->getCurrentOrganization(),
        ]);
    }
}
