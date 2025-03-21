<?php

namespace App\Livewire\Organization;

use App\DTO\Invite\CreateInviteDTO;
use App\DTO\Invite\DeleteInviteDTO;
use App\Enums\EventEnum;
use App\Livewire\Traits\WithSorting;
use App\Models\Organization;
use App\Services\InviteService;
use App\Services\OrganizationService;
use App\Services\UserService;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Invite extends Component
{
    use WithPagination, WithSorting;

    public ?string $email = '';

    public ?int $role_id;

    public Organization $organization;

    public function mount(UserService $userService, OrganizationService $organizationService)
    {
        $this->organization = $userService->getCurrentOrganization();
        $this->role_id = $organizationService->getDefaultRoleId();
    }

    public function rules()
    {
        return CreateInviteDTO::rawRules($this->organization->id);
    }

    protected function messages()
    {
        return [
            'email.unique' => "$this->email was already invited",
        ];
    }

    public function send(UserService $userService, InviteService $inviteService)
    {
        $this->validate();

        $invite = $inviteService->create(new CreateInviteDTO(
            user: Auth::user(),
            organization: $userService->getCurrentOrganization(),
            email: $this->email,
            role_id: $this->role_id,
        ));

        $this->reset('email');
        Flux::toast(variant: 'success', text: 'Invite sent');
        $this->dispatch(EventEnum::INVITE_CREATED->value, inviteId: $invite->id);

    }

    public function delete(UserService $userService, InviteService $inviteService, int $inviteId)
    {
        $invite = $inviteService->delete(new DeleteInviteDTO(
            user: Auth::user(),
            organization: $userService->getCurrentOrganization(),
            invite_id: $inviteId,
        ));

        Flux::toast(variant: 'success', text: 'Invite canceled');
        $this->dispatch(EventEnum::INVITE_DELETED->value, inviteId: $inviteId);
    }

    public function resend(UserService $userService, InviteService $inviteService, int $inviteId)
    {
        try {
            $inviteService->sendNotificationUsingId(
                user: Auth::user(),
                organization: $userService->getCurrentOrganization(),
                id: $inviteId,
            );

            Flux::toast(variant: 'success', text: 'Invite sent');
        } catch (\Throwable $th) {
            Flux::toast(variant: 'danger', text: 'Something went wrong');
        }
    }

    protected function list(UserService $userService, InviteService $inviteService)
    {
        return $inviteService->list(
            organization: $userService->getCurrentOrganization(),
            sortBy: $this->sortBy,
            sortDirection: $this->sortDirection
        );
    }

    public function render(UserService $userService, InviteService $inviteService)
    {
        return view('livewire.organization.invite', [
            'organization' => $userService->getCurrentOrganization(),
            'invites' => $this->list($userService, $inviteService),
        ]);
    }
}
