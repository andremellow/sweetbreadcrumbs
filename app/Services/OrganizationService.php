<?php

namespace App\Services;

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\Organization;
use App\Models\User;

class OrganizationService
{
    //  Organization $organization;

    /**
     * OrganizationService Construct.
     *
     * @param CreateOrganization $createOrganization
     *
     * @return OrganizationService
     */
    public function __construct(protected UserService $userService, protected CreateOrganization $createOrganization, protected ?Organization $organization = null)
    {
        $this->organization = $userService->getCurrentOrganization();
    }

    /**
     * Set organization.
     *
     * @param Organization $organization
     *
     * @return OrganizationService
     */
    public function setOrganization(Organization $organization): OrganizationService
    {
        $this->organization = $organization;

        return $this;
    }

    // /**
    //  * Get organization.
    //  *
    //  * @return Organization|null
    //  */
    // public function getOrganization(): ?Organization
    // {
    //     return $this->organization ?? null;
    // }

    /**
     * Creates a new organization.
     *
     * @param User                  $user
     * @param CreateOrganizationDTO $createOrganizationDTO
     *
     * @return Organization
     */
    public function create(User $user, CreateOrganizationDTO $createOrganizationDTO): Organization
    {
        return ($this->createOrganization)($user, $createOrganizationDTO, $this);
    }

    /**
     * Attach user to the given organization.
     *
     * @param Organization $organization
     * @param User         $user
     * @param int          $roleId
     *
     * @return void
     */
    public function attachUser(Organization $organization, User $user, int $roleId): void
    {
        $organization->users()->attach($user, [
            'role_id' => $roleId,
        ]);
    }

    /**
     * Get Organization's Priorities.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPrioritiesDropDownData(): \Illuminate\Support\Collection
    {
        return $this->organization->priorities()->select('id', 'name')->get()->pluck('name', 'id');
    }

    /**
     * Get Organization's Roles.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRolesDropDownData(): \Illuminate\Support\Collection
    {
        return $this->organization->roles()->select('id', 'name')->get()->pluck('name', 'id');
    }

    /**
     * Get Organization's Releases.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getReleasesDropDownData(): \Illuminate\Support\Collection
    {
        return $this->organization->releases()->select('id', 'name')->get()->pluck('name', 'id');
    }

    /**
     * Get Default role Id.
     *
     * @return int
     */
    public function getDefaultRoleId(): int
    {
        $role = $this->organization->roles()->where('is_default', true)->first();
        if (! $role) {
            $role = $this->organization->roles()->first();
        }

        return $role->id;
    }
}
