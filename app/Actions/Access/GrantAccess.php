<?php

namespace App\Actions\Access;

use App\DTO\Access\GrantAccessDTO;
use App\Exceptions\GrantAccessException;
use App\Models\User;
use App\Services\OrganizationService;

class GrantAccess
{
    /**
     * Close a task.
     *
     * @param GrantAccessDTO $grantAccessDTO,
     *
     * @return User
     */
    public function __invoke(GrantAccessDTO $grantAccessDTO, OrganizationService $organizationService
    ): void {

        if ($this->userDoesNotBelongToSameOrganizationOfAccessible($grantAccessDTO, $organizationService)) {
            throw new GrantAccessException(__('User does not belong to this organization.'));
        }

        if ($this->userAlreadyBelongsToAccessible($grantAccessDTO)) {
            throw new GrantAccessException(__('Access granted already.'));
        }

        if ($this->roleAllowed($grantAccessDTO, $organizationService)) {
            throw new GrantAccessException(__('Access granted already.'));
        }



        $grantAccessDTO->accessible->accesses()->create([
            'user_id' => $grantAccessDTO->user->id,
            'role_id' => $grantAccessDTO->roleId,
        ]);
    }

    protected function userDoesNotBelongToSameOrganizationOfAccessible(GrantAccessDTO $grantAccessDTO, OrganizationService $organizationService): bool
    {
        return $organizationService->setOrganization(
            $grantAccessDTO->accessible->organization
        )->emailBelongsToOrganization($grantAccessDTO->user->email);

    }

    protected function userAlreadyBelongsToAccessible(GrantAccessDTO $grantAccessDTO): bool
    {
        return $grantAccessDTO
            ->accessible
            ->accesses()
            ->where('user_id', $grantAccessDTO->user->id)
            ->exists();
    }

    protected function roleAllowed(GrantAccessDTO $grantAccessDTO, OrganizationService $organizationService): bool
    {

    }
}
