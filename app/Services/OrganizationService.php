<?php

namespace App\Services;

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Enums\RoleEnum;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

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

    public function getUserByEmail(string $email): User
    {
        return $this->organization
            ->users()
            ->where('email', $email)
            ->firstOrFail();

    }

    public function emailBelongsToOrganization(string $email): bool
    {
        return $this->organization
            ->users()
            ->where('email', $email)
            ->exists();

    }

    public function getRoleForUser(User $user): Role
    {
        return Role::find(
            $this->organization
                ->users()
                ->findOrFail($user->id)
                ->pivot
                ->role_id
        );
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
        return $this->getRolesQuery()->get()->pluck('name', 'id');
    }

    /**
     * Get Organization's Roles.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRolesDropDownDataForUser(?User $user): \Illuminate\Support\Collection
    {

        $role = $user ? $this->getRoleForUser($user) : null;

        return $this->getRolesQuery()
            ->when($role, function (Builder $builder, Role $role) {
                match ($role->id) {
                    RoleEnum::ADMIN->value => $builder->where('id', RoleEnum::ADMIN->value),
                    RoleEnum::VIEWER->value => $builder->where('id', RoleEnum::VIEWER->value),
                    default => $builder
                };

            }, function (Builder $builder) {
                $builder->where('id', RoleEnum::VIEWER->value);
            })
            ->get()->pluck('name', 'id');
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
     * Get List Of Roles.
     *
     * @return Builder
     */
    public function getRolesQuery(): Builder
    {
        return Role::where(function (Builder $query) {
            $query->when($this->organization, function (Builder $query, Organization $organization) {
                $query->where('organization_id', $organization->id);
            })->orWhere('organization_id', config('app.demo_organization_id'));
        })->select('id', 'name');
    }
}
