<?php

namespace App\Actions\Access;

use App\DTO\Access\GrantAccessDTO;
use App\Enums\RoleEnum;
use App\Exceptions\GrantAccessException;
use App\Models\Access;
use App\Services\OrganizationService;

class GrantAccess
{
    /**
     * Grant Access.
     *
     * @param GrantAccessDTO      $grantAccessDTO      ,
     * @param OrganizationService $organizationService ,
     *
     * @throws GrantAccessException
     */
    public function __invoke(GrantAccessDTO $grantAccessDTO, OrganizationService $organizationService
    ): Access {

        if ($this->userDoesNotBelongToSameOrganizationOfAccessible($grantAccessDTO, $organizationService)) {
            throw new GrantAccessException(__('User does not belong to this organization.'));
        }

        if ($this->userAlreadyBelongsToAccessible($grantAccessDTO)) {
            throw new GrantAccessException(__('Access granted already.'));
        }

        if ($this->roleNotAllowed($grantAccessDTO, $organizationService)) {
            throw new GrantAccessException(__('Role not allowed.'));
        }

        return $grantAccessDTO->accessible->accesses()->create([
            'user_id' => $grantAccessDTO->user->id,
            'role_id' => $grantAccessDTO->role->value,
        ]);
    }

    protected function userDoesNotBelongToSameOrganizationOfAccessible(GrantAccessDTO $grantAccessDTO, OrganizationService $organizationService): bool
    {
        return ! $organizationService->setOrganization(
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

    protected function roleNotAllowed(GrantAccessDTO $grantAccessDTO, OrganizationService $organizationService): bool
    {

        $role = $organizationService->setOrganization(
            $grantAccessDTO->accessible->organization
        )->getRoleForUser($grantAccessDTO->user);

        $userRole = RoleEnum::from($role->id);

        if ($userRole === RoleEnum::ADMIN && $grantAccessDTO->role !== RoleEnum::ADMIN) {
            return true;
        } elseif ($userRole === RoleEnum::VIEWER && $grantAccessDTO->role !== RoleEnum::VIEWER) {
            return true;
        }

        return false;

    }
}
