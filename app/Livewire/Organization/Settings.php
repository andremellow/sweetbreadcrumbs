<?php

namespace App\Livewire\Organization;

use App\Services\UserService;
use Livewire\Component;

class Settings extends Component
{
    public function render(UserService $userService)
    {
        return view('livewire.organization.settings', [
            'organization' => $userService->getCurrentOrganization(),
        ]);
    }
}
