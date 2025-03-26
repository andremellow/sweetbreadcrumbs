<?php

namespace App\Services;

use App\Models\Invite;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Context;

class UserService
{
    protected Organization $organization;

    /**
     * UserService Construct.
     *
     * @param User $user
     *
     * @return UserService
     */
    public function __construct(protected User $user) {}

    /**
     * Set user to the class.
     *
     * @param User $user
     *
     * @return UserService
     */
    public function setUser(User $user): UserService
    {
        $this->user = $user;

        return $this;
    }

    /**
     * get user from the class.
     *
     * @return ?User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set user to the class.
     *
     * @param string $slug
     *
     * @return UserService
     */
    public function setOrganization(Organization $organization): UserService
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Check if the given user has organizations.
     *
     * @return bool
     */
    public function hasOrganizations(): bool
    {
        return $this->user->organizations()->count() > 0;
    }

    /**
     * Check if the given user has organizations.
     *
     * @return bool
     */
    public function hasOrganization(int $organizationId): bool
    {
        return $this->user->organizations()->where('organization_id', $organizationId)->exists();
    }

    /**
     * Get user's current Organizations.
     *
     * @return Organization | null
     */
    public function getCurrentOrganization(): ?Organization
    {
        /**
         * Using session and contex. Session fail for most of test.
         */
        if (isset($this->organization) && $this->organization instanceof Organization) {
            return $this->organization;
        }

        if (request()->hasSession() && request()->session()->has('current_organization')) {
            return request()->session()->get('current_organization');
        }

        if (Context::get('current_organization')) {
            return Context::get('current_organization');
        }

        return $this->getOrganizations()->first();
    }

    /**
     * Get all user's organizations.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Organization>
     */
    public function getOrganizations(): Collection
    {
        return $this->user->organizations()->get();
    }

    /**
     * Get all user's workstreams.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Organization>
     */
    public function getWorkstreams(): Collection
    {
        return $this->getCurrentOrganization()?->workstreams()->orderBy('name')->get();
    }

    /**
     * Get all user's invites.
     *
     * @return LengthAwarePaginator
     */
    public function getInvites(): LengthAwarePaginator
    {
        return Invite::where('email', $this->user->email)->paginate(config('app.pagination_items'));
    }

    /**
     * Get user's invite by id.
     *
     * @return Invite
     */
    public function getInviteById(int $id): Invite
    {
        return Invite::where('email', $this->user->email)->findOrFail($id);
    }

    public static function getOrganizationBySlug(User $user, string $slug): ?Organization
    {
        return $user->organizations()->where('slug', $slug)->first();
    }
}
