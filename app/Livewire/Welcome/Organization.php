<?php

namespace App\Livewire\Welcome;

use App\DTO\Organization\CreateOrganizationDTO;
use App\Services\OrganizationService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Organization extends Component
{
    public string $name;

    public function rules(): array
    {
        return CreateOrganizationDTO::rules();
    }

    public function create(OrganizationService $organizationService): void
    {
        $this->validate();

        $organization = $organizationService->create(
            Auth::user(),
            new CreateOrganizationDTO(name: $this->name)
        );

        $this->redirect(route('welcome.workstream', ['organization' => $organization->slug]));
    }

    #[Layout('components.layouts.welcome')]
    public function render(): View
    {
        return view('livewire.welcome.organization');
    }
}
