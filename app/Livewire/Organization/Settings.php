<?php

namespace App\Livewire\Organization;

use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Settings extends Component
{
    public function render(UserService $userService): View
    {
        return view('livewire.organization.settings', [
            'organization' => $userService->getCurrentOrganization(),
        ]);
    }
}
