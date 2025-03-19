<?php

namespace App\Services;

use App\Actions\Invite\CreateInvite;
use App\Actions\Invite\DeleteInvite;
use App\Actions\Invite\UpdateInvite;
use App\DTO\Invite\CreateInviteDTO;
use App\DTO\Invite\DeleteInviteDTO;
use App\DTO\Invite\UpdateInviteDTO;
use App\Enums\SortDirection;
use App\Models\Invite;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InviteService
{
    /**
     * OrganizationService Construct.
     *
     * @param CreateInvite $createInvite
     *
     * @return OrganizationService
     */
    public function __construct(protected CreateInvite $createInvite, protected DeleteInvite $deleteInvite) {}

    public function list(
        Organization $organization,
        string $sortBy = 'sent_at',
        SortDirection $sortDirection = SortDirection::ASC
    ): LengthAwarePaginator {
        switch ($sortBy) {
            case 'role':
                $sortBy = 'roles.name';
                break;
            case 'email':
                $sortBy = 'email';
                break;
            default:
                $sortBy = 'sent_at';
                break;
        }

        return $organization->invites()
            ->with(['role:id,name'])
            ->orderBy($sortBy, $sortDirection->value)
            ->paginate(config('app.pagination_items'));
    }

    /**
     * Creates a new invite.
     *
     * @param Use             $user,
     * @param CreateInviteDTO $createInviteDTO,
     *
     * @return Invite
     */
    public function create(
        CreateInviteDTO $createInviteDTO
    ): Invite {

        return ($this->createInvite)(
            $createInviteDTO
        );
    }

    // /**
    //  * Update an existing workstream.
    //  *
    //  * @param UpdateInviteDTO $updateInviteDTO
    //  *
    //  * @return Invite
    //  */
    // public function update(
    //     User $user,
    //     UpdateInviteDTO $updateInviteDTO
    // ): Invite {
    //     return ($this->updateInvite)(
    //         $updateInviteDTO
    //     );
    // }

    /**
     * Delete an existing workstream.
     *
     * @param User            $user,
     * @param DeleteInviteDTO $deleteInviteDTO
     *
     * @return Invite
     */
    public function delete(
        DeleteInviteDTO $deleteInviteDTO
    ): void {
        ($this->deleteInvite)(
            $deleteInviteDTO
        );
    }
}
