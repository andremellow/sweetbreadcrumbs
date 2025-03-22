<?php

namespace App\Livewire\Welcome;

use App\DTO\Workstream\CreateWorkstreamDTO;
use App\Enums\ConfigEnum;
use App\Services\ConfigService;
use App\Services\UserService;
use App\Services\WorkstreamService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Workstream extends Component
{
    public string $name;

    public string $priority_id;

    public function mount(ConfigService $configService, UserService $userService): void
    {
        $this->priority_id = $configService->get(ConfigEnum::WORKSTREAM_DEFAULT_PRIORITY_ID);
    }

    public function rules(): array
    {
        return CreateWorkstreamDTO::rules();
    }

    public function create(UserService $userService, WorkstreamService $workstreamService): void
    {
        $this->validate();

        $workstream = $workstreamService->create(
            Auth::user(),
            new CreateWorkstreamDTO(
                organization: $userService->getCurrentOrganization(),
                name: $this->name,
                priority_id: $this->priority_id
            )
        );
        $this->redirect(route('workstreams.dashboard', ['organization' => $userService->getCurrentOrganization()->slug, 'workstream' => $workstream]));
    }

    #[Layout('components.layouts.welcome')]
    public function render(): View
    {
        return view('livewire.welcome.workstream');
    }
}
