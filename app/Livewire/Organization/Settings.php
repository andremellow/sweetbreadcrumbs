<?php

namespace App\Livewire\Organization;

use App\Services\OrganizationService;
use Livewire\Component;

class Settings extends Component
{
    public function render(OrganizationService $organizationService)
    {
        return view('livewire.organization.settings', [
            'organization' => $organizationService->getOrganization(),
        ]);
    }
}
