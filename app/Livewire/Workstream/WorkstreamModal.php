<?php

namespace App\Livewire\Workstream;

use App\Enums\EventEnum;
use App\Livewire\Forms\WorkstreamForm;
use App\Services\UserService;
use App\Services\WorkstreamService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class WorkstreamModal extends Component
{
    public WorkstreamForm $form;

    public bool $showWorkstreamFormModal = false;

    #[On([EventEnum::LOAD_WORKSTREAM_FORM_MODAL->value])]
    public function load(?int $workstreamId = null): void
    {
        $this->form->maybeLoadWorkstream($workstreamId);

        $this->showWorkstreamFormModal = true;
    }

    public function onModalClose(): void
    {
        $this->form->reset();
    }

    public function save(UserService $userService, WorkstreamService $workstreamService): void
    {
        if ($this->form->id === null) {
            $workstream = $this->form->add($userService, $workstreamService);
            $this->dispatch(EventEnum::WORKSTREAM_CREATED->value, workstreamId: $workstream->id);
        } else {
            $this->form->edit($userService, $workstreamService);
            $this->dispatch(EventEnum::WORKSTREAM_UPDATED->value, workstreamId: $this->form->id);
        }

        $this->reset();
        $this->showWorkstreamFormModal = false;
    }

    public function render(UserService $userService): View
    {
        return view('livewire.workstream.workstream-modal', [
            'organization' => $userService->getCurrentOrganization(),
        ]);
    }
}
