<?php

namespace App\Livewire\Organization;

use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(UserService $userService): View
    {
        return view('livewire.organization.dashboard', [
            'organization' => $userService->getCurrentOrganization(),
        ]);
    }
}
