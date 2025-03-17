<?php

namespace App\Livewire\Welcome;

use App\DTO\Workstream\CreateWorkstreamDTO;
use App\Enums\ConfigEnum;
use App\Services\ConfigService;
use App\Services\OrganizationService;
use App\Services\WorkstreamService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Workstream extends Component
{
    public string $name;

    public string $priority_id;

    public $organization;

    public function mount(ConfigService $configService, OrganizationService $organizationService)
    {
        $this->priority_id = $configService->get(ConfigEnum::WORKSTREAM_DEFAULT_PRIORITY_ID);
        $this->organization = $organizationService->getOrganization();
    }

    public function rules()
    {
        return CreateWorkstreamDTO::rules();
    }

    public function create(WorkstreamService $workstreamService)
    {
        $this->validate();

        $workstream = $workstreamService->create(
            auth()->user(),
            new CreateWorkstreamDTO(
                organization: $this->organization,
                name: $this->name,
                priority_id: $this->priority_id
            )
        );
        $this->redirect(route('workstreams.dashboard', ['organization' => $this->organization->slug, 'workstream' => $workstream]));
    }

    #[Layout('components.layouts.welcome')]
    public function render()
    {
        return view('livewire.welcome.workstream');
    }
}
