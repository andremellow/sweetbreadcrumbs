<?php

namespace App\Livewire\Workstream;


use App\Models\User;
use App\Models\Workstream;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class WorkstreamMembersModal extends Component
{

    public Workstream $workstream;

    public string $email;

    public string $search = '';

    public function onModalClose()
    {

    }

    public function add()
    {
        dd($this->email);
    }

    public function getAutocomplete()
    {
        if(empty($this->search)) {
            return [];
        }

        return User::where('first_name', 'LIKE', "%{$this->search}%")
                ->orWhere('last_name', 'LIKE', "%{$this->search}%")
                ->orWhere('email', 'LIKE', "%{$this->search}%")
            ->get();
    }



    public function render(UserService $userService): View
    {
        return view('livewire.workstream.workstream-members-modal', [
            'organization' => $userService->getCurrentOrganization(),
            'autocomplete' => $this->getAutocomplete(),

            'members' => []
        ]);
    }
}
