<?php

namespace App\Livewire;

use App\Services\OrganizationService;
use Livewire\Component;

class PriorityDropdown extends Component
{
    public $eventName = 'onPriorityDropdownSelected';

    public $priorityId;

    public function updatedPriorityId()
    {
        $this->dispatch($this->eventName, priorityId: empty($this->priorityId) ? null : $this->priorityId);
    }

    public function render(OrganizationService $organizationService)
    {
        return view('livewire.priority-dropdown', [
            'priorities' => $organizationService->getPrioritiesDropDownData(),
        ]);
    }
}
