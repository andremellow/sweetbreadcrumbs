<?php

namespace App\Livewire\Welcome;

use App\DTO\Workstream\CreateWorkstreamDTO;
use App\Enums\ConfigEnum;
use App\Services\ConfigService;
use App\Services\UserService;
use App\Services\WorkstreamService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Workstream extends Component
{
    public string $name;

    public string $priority_id;

    public function mount(ConfigService $configService, UserService $userService)
    {
        $this->priority_id = $configService->get(ConfigEnum::WORKSTREAM_DEFAULT_PRIORITY_ID);
    }

    public function rules()
    {
        return CreateWorkstreamDTO::rules();
    }

    public function create(UserService $userService, WorkstreamService $workstreamService)
    {
        $this->validate();

        $workstream = $workstreamService->create(
            auth()->user(),
            new CreateWorkstreamDTO(
                organization: $userService->getCurrentOrganization(),
                name: $this->name,
                priority_id: $this->priority_id
            )
        );
        $this->redirect(route('workstreams.dashboard', ['organization' => $userService->getCurrentOrganization()->slug, 'workstream' => $workstream]));
    }

    #[Layout('components.layouts.welcome')]
    public function render()
    {
        return view('livewire.welcome.workstream');
    }
}
