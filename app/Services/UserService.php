<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
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
     * Get user's current Organizations.
     *
     * @return Organization | null
     */
    public function getCurrentOrganization(): ?Organization
    {
        return $this->organization ?? Context::get('current_organization');
    }

    /**
     * Get all user's organizations.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Organization>
     */
    public function getOrganizations()
    {
        return $this->user->organizations()->get();
    }

    /**
     * Get all user's workstreams.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Organization>
     */
    public function getWorkstreams()
    {
        return $this->getCurrentOrganization()?->workstreams()->orderBy('name')->get();
    }

    public static function getOrganizationBySlug(User $user, $slug): ?Organization
    {
        return $user->organizations()->where('slug', $slug)->first();
    }
}
