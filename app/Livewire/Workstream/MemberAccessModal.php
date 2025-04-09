<?php

namespace App\Livewire\Workstream;

use App\Contracts\AccessibleContract;
use App\DTO\Access\GrantAccessDTO;
use App\DTO\Access\RevokeAccessDTO;
use App\Enums\EventEnum;
use App\Enums\RoleEnum;
use App\Models\User;
use App\Services\AccessService;
use App\Services\OrganizationService;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class MemberAccessModal extends Component
{
    use withPagination;

    public AccessibleContract $accessible;

    public string $email;

    public int $roleId;

    public string $search = '';

    public bool $showMemberAccessModal = false;

    public ?User $user;

    public ?string $roleName;

    public function onModalClose(): void {}

    public function updatedEmail(OrganizationService $organizationService): void
    {
        $this->user = $organizationService->getUserByEmail($this->email);
        $this->search = $this->user->full_name;
    }

    public function add(AccessService $accessService, OrganizationService $organizationService): void
    {
        $this->validate([
            'email' => 'required|email',
            'roleId' => 'required',
        ]);

        $accessService->grantAccess(
            grantAccessDTO: new GrantAccessDTO(
                accessible: $this->accessible,
                user: $this->user,
                role: RoleEnum::from($this->roleId)
            ),
            organizationService: $organizationService,
        );

        $this->reset('roleId', 'roleName', 'search', 'email');
        $this->dispatch(EventEnum::ACCESS_GRANTED);
    }

    public function delete(AccessService $accessService, int $userId): void
    {
        $accessService->revokeAccess(new RevokeAccessDTO(
            accessible: $this->accessible,
            user_id: $userId
        ));

        $this->dispatch(EventEnum::ACCESS_REVOKED);
    }

    public function getAutocomplete(AccessService $accessService): array|Collection
    {
        if (empty($this->search)) {
            return [];
        }

        return $accessService->listAutoComplete($this->accessible, $this->search);
    }

    #[Computed]
    public function members(): LengthAwarePaginator
    {
        $accessServices = app(AccessService::class);

        return $accessServices->list(
            accessible: $this->accessible
        );

    }

    public function render(UserService $userService, AccessService $accessService): View
    {
        return view('livewire.workstream.member-access-modal', [
            'organization' => $userService->getCurrentOrganization(),
            'autocomplete' => $this->getAutocomplete($accessService),
        ]);
    }
}
