<?php

namespace App\Livewire\Welcome;

use App\DTO\Organization\CreateOrganizationDTO;
use App\Services\OrganizationService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Organization extends Component
{
    public string $name;

    public function rules()
    {
        return CreateOrganizationDTO::rules();
    }

    public function create(OrganizationService $organizationService)
    {
        $this->validate();

        $organization = $organizationService->create(
            auth()->user(),
            new CreateOrganizationDTO(name: $this->name)
        );

        $this->redirect(route('welcome.workstream', ['organization' => $organization->slug]));
    }

    #[Layout('components.layouts.welcome')]
    public function render()
    {
        return view('livewire.welcome.organization');
    }
}
