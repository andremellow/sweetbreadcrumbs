<?php

namespace App\Livewire\Workstream;

use App\Enums\EventEnum;
use App\Livewire\Forms\WorkstreamForm;
use App\Models\Organization;
use App\Services\OrganizationService;
use App\Services\WorkstreamService;
use Livewire\Attributes\On;
use Livewire\Component;

class WorkstreamModal extends Component
{
    public WorkstreamForm $form;

    public $showWorkstreamFormModal = false;

    public ?Organization $organization;

    public function mount(OrganizationService $organizationService)
    {
        $this->organization = $organizationService->getOrganization();
    }

    #[On([EventEnum::LOAD_WORKSTREAM_FORM_MODAL->value])]
    public function load(?int $workstreamId = null)
    {
        $this->form->maybeLoadWorkstream($workstreamId);

        $this->showWorkstreamFormModal = true;
    }

    public function onModalClose()
    {
        $this->form->reset();
    }

    public function save(OrganizationService $organizationService, WorkstreamService $workstreamService)
    {
        $organizationService->setOrganization($this->organization);

        if ($this->form->id === null) {
            $workstream = $this->form->add($organizationService, $workstreamService);
            $this->dispatch(EventEnum::WORKSTREAM_CREATED->value, workstreamId: $workstream->id);
        } else {
            $this->form->edit($organizationService, $workstreamService);
            $this->dispatch(EventEnum::WORKSTREAM_UPDATED->value, workstreamId: $this->form->id);
        }

        $this->reset();
        $this->showWorkstreamFormModal = false;
    }

    public function render(OrganizationService $organizationService)
    {
        return view('livewire.workstream.workstream-modal', [
            'organization' => $organizationService->getOrganization(),
        ]);
    }
}
