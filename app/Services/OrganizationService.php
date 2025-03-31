<?php

namespace App\Services;

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class OrganizationService
{
    protected ?Organization $organization;

    /**
     * OrganizationService Construct.
     *
     * @param CreateOrganization $createOrganization
     *
     * @return OrganizationService
     */
    public function __construct(protected UserService $userService, protected CreateOrganization $createOrganization)
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
        return  $this->getRolesQuery()->get()->pluck('name', 'id');
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
            $role = Role::where('organization_id', config('app.demo_organization_id'))->where('is_default', true)->first();
        }

        if (! $role) {
            $role = Role::where('organization_id', config('app.demo_organization_id'))->first();
        }

        return $role->id;
    }

    /**
     * Get List Of Roles
     *
     * @return Builder
     */
    public function getRolesQuery(): Builder
    {
        return Role::where(function(Builder $query) {
            $query->when($this->organization, function(Builder $query, Organization $organization) {
                $query->where('organization_id', $organization->id);
            })->orWhere('organization_id', config('app.demo_organization_id'));
        })->select('id', 'name');
    }
}
