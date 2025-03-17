<?php

namespace App\Livewire\Organization;

use App\Services\OrganizationService;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(OrganizationService $organizationService)
    {
        return view('livewire.organization.dashboard', [
            'organization' => $organizationService->getOrganization(),
        ]);
    }
}
