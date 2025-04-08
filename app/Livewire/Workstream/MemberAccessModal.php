<?php

namespace App\Livewire\Workstream;

use App\Contracts\AccessibleContract;
use App\Enums\RoleEnum;
use App\Models\User;
use App\Services\AccessService;
use App\Services\OrganizationService;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class MemberAccessModal extends Component
{
    use withPagination;

    public AccessibleContract $accessible;

    public string $email;

    public int $roleId = 3;

    public string $search = '';

    public bool $showMemberAccessModal = false;

    public ?User $user;

    public ?string $roleName;

    public function onModalClose() {}

    public function updatedEmail(OrganizationService $organizationService): void
    {
        $this->user = $organizationService->getUserByEmail($this->email);
        $role = $organizationService->getRoleForUser($this->user);

        if ($role->id === RoleEnum::ADMIN->value || $role->id === RoleEnum::VIEWER->value) {
            $this->roleId = $role->id;
            $this->roleName = $role->name;
        } else {
            $this->reset('roleId', 'roleName');
        }
    }

    public function add(AccessService $accessService): void
    {

        if ($this->accessible->accesses()->where('user_id', $this->user->id)->exists() === false) {

            $this->accessible->accesses()->create([
                'user_id' => $this->user->id,
                'role_id' => $this->roleId,
            ]);
        }

        $this->reset('roleId', 'roleName', 'search', 'email');
    }

    public function delete(int $userId): void
    {
        $this->accessible->accesses()->where('user_id', $userId)->delete();
    }

    public function getAutocomplete(AccessService $accessService): array|Collection
    {
        if (empty($this->search)) {
            return [];
        }

        return $accessService->listAutoComplete($this->accessible, $this->search);
    }

    #[Computed]
    public function members()
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
