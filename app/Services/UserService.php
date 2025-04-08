<?php

namespace App\Services;

use App\Enums\CapabilityEnum;
use App\Models\Invite;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
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
     * @return Collection<int, Organization>
     */
    public function getOrganizations(): Collection
    {
        return $this->user->organizations()->get();
    }

    /**
     * Get all user's workstreams.
     *
     * @return Collection<int, Organization>
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

    /**
     * Retrieve the organization associated with the given slug for the specified user.
     *
     * @param User   $user The user whose organization is being retrieved.
     * @param string $slug The slug of the organization to retrieve.
     *
     * @return Organization|null The organization if found, otherwise null.
     */
    public static function getOrganizationBySlug(User $user, string $slug): ?Organization
    {
        return $user->organizations()->where('slug', $slug)->first();
    }

    public function getCurrentRole(): Role
    {


            dd($this->getCurrentOrganization());

    }

    public function getCapabilities(): Collection
    {
        return Cache::remember("user.capabilities.{$this->user->id}.{$this->getCurrentOrganization()->id}", 60, function () {

            return Role::find($this->getCurrentOrganization()->pivot->role_id)->capabilities;
        });
    }

    public function can(CapabilityEnum $capability): bool
    {
        return $this->getCapabilities()->pluck('id')->contains($capability->value);
    }
}
