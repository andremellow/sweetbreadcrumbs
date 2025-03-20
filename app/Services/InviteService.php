<?php

namespace App\Services;

use App\Actions\Invite\CreateInvite;
use App\Actions\Invite\DeleteInvite;
use App\Actions\Invite\UpdateInviteSent;
use App\DTO\Invite\CreateInviteDTO;
use App\DTO\Invite\DeleteInviteDTO;
use App\DTO\Invite\UpdateInviteSentDTO;
use App\Enums\SortDirection;
use App\Models\Invite;
use App\Models\Organization;
use App\Models\User;
use App\Notifications\InviteCreatedNotification;
use Exception;
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
    public function __construct(
        protected CreateInvite $createInvite,
        protected DeleteInvite $deleteInvite,
        protected UpdateInviteSent $updateInviteSent
    ) {}

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
            ->leftJoin('roles', 'invites.role_id', '=', 'roles.id')
            ->with(['role:id,name'])
            ->orderBy($sortBy, $sortDirection->value)
            ->select('invites.*')
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

        $invite = ($this->createInvite)(
            $createInviteDTO
        );

        $this->sendNotification($createInviteDTO->user, $invite);

        return $invite;
    }

    /**
     * Creates a new invite.
     *
     * @param Use             $user,
     * @param CreateInviteDTO $createInviteDTO,
     *
     * @return Invite
     */
    public function sendNotification(User $user, Invite $invite): void
    {
        if ($invite->wasRecentlyCreated === false && ! $invite->can_resend) {
            throw new Exception("Invite $invite->id try to resent before the time allowed! Something wrong.");
        }

        $invite->notify(new InviteCreatedNotification($this));

        ($this->updateInviteSent)(new UpdateInviteSentDTO(
            user: $user,
            organization: $invite->organization,
            invite_id: $invite->id
        ));
    }

    /**
     * Send notification from id.
     *
     * @param Organization $organization,
     * @param int          $id
     *
     * @return Invite
     */
    public function sendNotificationUsingId(
        User $user,
        Organization $organization,
        int $id
    ): void {
        $this->sendNotification(
            $user,
            $this->get($organization, $id)
        );
    }

    /**
     * Get a invite by id.
     *
     * @param Organization $organization,
     * @param int          $id
     *
     * @return Invite
     */
    public function get(
        Organization $organization,
        int $id
    ): Invite {
        return $organization->invites()->find($id);
    }

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
