<?php

namespace App\Actions\Invite;

use App\DTO\Invite\AcceptInviteDTO;
use App\Models\User;
use App\Services\OrganizationService;
use Exception;

class AcceptInvite
{
    /**
     * Close a task.
     *
     * @param AcceptInviteDTO $createUserDTO,
     *
     * @return User
     */
    public function __invoke(AcceptInviteDTO $acceptInviteDTO, OrganizationService $organizationService
    ): void {
        if ($this->userIsAlreadyMemberOfOrganization($acceptInviteDTO)) {
            return;
        }

        if ($this->roleNotExists($acceptInviteDTO)) {
            throw new Exception(__('Invite role does not belongs to the organization'));
        }

        if ($this->inviteDoesNotBelongsToSameEmail($acceptInviteDTO)) {
            throw new Exception(__('Invite email does not belongs to authenticated user'));
        }

        $organizationService->attachUser(
            organization: $acceptInviteDTO->invite->organization,
            user: $acceptInviteDTO->user,
            roleId: $acceptInviteDTO->invite->role_id
        );
    }

    protected function userIsAlreadyMemberOfOrganization(AcceptInviteDTO $acceptInviteDTO): bool
    {
        return $acceptInviteDTO
            ->invite
            ->organization
            ->users()
            ->where('email', strtolower($acceptInviteDTO->user->email))
            ->exists();
    }

    protected function roleNotExists(AcceptInviteDTO $acceptInviteDTO): bool
    {
        return $acceptInviteDTO
            ->invite
            ->organization
            ->roles()
            ->where('id', $acceptInviteDTO->invite->role_id)
            ->exists() === false;
    }

    protected function inviteDoesNotBelongsToSameEmail(AcceptInviteDTO $acceptInviteDTO): bool
    {
        return $acceptInviteDTO->user->email !== $acceptInviteDTO->invite->user;
    }
}
