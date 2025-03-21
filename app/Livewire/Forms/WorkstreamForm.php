<?php

namespace App\Livewire\Forms;

use App\DTO\Workstream\CreateWorkstreamDTO;
use App\DTO\Workstream\UpdateWorkstreamDTO;
use App\Models\Workstream;
use App\Services\UserService;
use App\Services\WorkstreamService;
use Livewire\Form;

class WorkstreamForm extends Form
{
    public ?int $id = null;

    public string $name = '';

    public ?int $priority_id = null;

    protected function rules()
    {
        return CreateWorkstreamDTO::rules();
    }

    public function add(UserService $userService, WorkstreamService $workstreamService)
    {
        $validated = $this->validate();

        return $workstreamService->create(
            auth()->user(),
            CreateWorkstreamDTO::from([
                'organization' => $userService->getCurrentOrganization(),
                ...$validated,
            ])
        );
    }

    public function edit(UserService $userService, WorkstreamService $workstreamService)
    {
        $validated = $this->validate();

        $workstreamService->update(
            auth()->user(),
            UpdateWorkstreamDTO::from([
                'organization' => $userService->getCurrentOrganization(),
                'workstream_id' => $this->id,
                ...$validated,
            ])
        );

        $this->reset();
    }

    public function maybeLoadWorkstream(?int $workstreamId)
    {
        if ($workstreamId !== null) {
            // TODO: VALIDETE THIS
            $workstream = Workstream::findOrFail($workstreamId);
            $this->id = $workstream->id;
            $this->name = $workstream->name;
            $this->priority_id = $workstream->priority_id;
        } else {
            $this->reset();
        }
    }
}
