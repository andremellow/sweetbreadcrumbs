<?php

namespace App\Livewire;

use App\Services\OrganizationService;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PriorityDropdown extends Component
{
    #[Modelable]
    public ?int $priorityId;

    public function render(OrganizationService $organizationService)
    {
        if(isset($this->hydratePriorityId)) {
            dd($this->hydratePriorityId);
        }
        return view('livewire.priority-dropdown', [
            'priorities' => $organizationService->getPrioritiesDropDownData(),
        ]);
    }
}
