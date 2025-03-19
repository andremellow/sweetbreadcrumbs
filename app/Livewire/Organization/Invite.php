<?php

namespace App\Livewire\Organization;

use App\DTO\Invite\CreateInviteDTO;
use App\DTO\Invite\DeleteInviteDTO;
use App\Enums\EventEnum;
use App\Livewire\Traits\WithSorting;
use App\Services\InviteService;
use App\Services\OrganizationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Invite extends Component
{
    use WithPagination, WithSorting;

    public ?string $email;

    public ?int $role_id;

    public function rules()
    {
        return CreateInviteDTO::rules();
    }

    public function send(OrganizationService $organizationService, InviteService $inviteService)
    {
        $this->validate();

        $invite = $inviteService->create(new CreateInviteDTO(
            user: Auth::user(),
            organization: $organizationService->getOrganization(),
            email: $this->email,
            role_id: $this->role_id,
        ));

        $this->reset('email');

        $this->dispatch(EventEnum::INVITE_CREATED->value, inviteId: $invite->id);

    }

    public function delete(OrganizationService $organizationService, InviteService $inviteService, int $inviteId)
    {
        $invite = $inviteService->delete(new DeleteInviteDTO(
            user: Auth::user(),
            organization: $organizationService->getOrganization(),
            invite_id: $inviteId,
        ));

        $this->dispatch(EventEnum::INVITE_DELETED->value, inviteId: $inviteId);

    }

    protected function list(OrganizationService $organizationService, InviteService $inviteService)
    {
        return $inviteService->list(
            organization: $organizationService->getOrganization(),
            sortBy: $this->sortBy,
            sortDirection: $this->sortDirection
        );
    }

    public function render(OrganizationService $organizationService, InviteService $inviteService)
    {
        return view('livewire.organization.invite', [
            'organization' => $organizationService->getOrganization(),
            'invites' => $this->list($organizationService, $inviteService),
        ]);
    }
}
