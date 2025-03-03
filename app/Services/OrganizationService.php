<?php

namespace App\Services;

use App\Actions\Organization\CreateOrganization;
use App\Models\Organization;
use App\Models\User;

class OrganizationService
{
    protected Organization $organization;

    /**
     * OrganizationService Construct.
     *
     * @param CreateOrganization $createOrganization
     *
     * @return OrganizationService
     */
    public function __construct(protected CreateOrganization $createOrganization) {}

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

    /**
     * Get organization.
     *
     * @return Organization|null
     */
    public function getOrganization(): ?Organization
    {
        return $this->organization ?? null;
    }

    /**
     * Creates a new organization.
     *
     * @param User   $user
     * @param string $name
     *
     * @return Organization
     */
    public function create(User $user, string $name): Organization
    {
        return ($this->createOrganization)($user, $name);
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
     * Get Organization's Releases.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getReleasesDropDownData(): \Illuminate\Support\Collection
    {
        return $this->organization->releases()->select('id', 'name')->get()->pluck('name', 'id');
    }
}
