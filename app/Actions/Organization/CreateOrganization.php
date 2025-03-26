<?php

namespace App\Actions\Organization;

use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\Organization;
use App\Models\Priority;
use App\Models\Probability;
use App\Models\RiskLevel;
use App\Models\RiskStatus;
use App\Models\Role;
use App\Models\User;
use App\Services\OrganizationService;
use Illuminate\Support\Str;

class CreateOrganization
{
    protected int $demoOrganizationId;

    /**
     * Creates new organization.
     *
     * @param User                  $user
     * @param CreateOrganizationDTO $createOrganizationDTO
     *
     * @return Organization
     */
    public function __invoke(User $user, CreateOrganizationDTO $createOrganizationDTO, OrganizationService $organizationService): Organization
    {
        $this->demoOrganizationId = config('app.demo_organization_id');
        $organization = Organization::create([
            'name' => $createOrganizationDTO->name,
            'slug' => $this->generateUniqueSlug($createOrganizationDTO->name),
        ]);

        $this->copyPriorities($organization);
        $this->copyRiskLevels($organization);
        $this->copyRiskStatuses($organization);
        $this->copyProbabilities($organization);
        $this->copyRoles($organization);

        $role = $organization->roles()->where('name', 'Admin')->first();

        $organizationService->attachUser(
            organization: $organization,
            user: $user,
            roleId: $role ? $role->id : $organizationService->getDefaultRoleId(),
        );

        return $organization;
    }

    protected function copyPriorities(Organization $organization): void
    {
        $prioritiesToCopy = Priority::where(['organization_id' => $this->demoOrganizationId])->get();
        foreach ($prioritiesToCopy as $priority) {
            $organization->priorities()->create([
                'name' => $priority->name,
                'order' => $priority->order,
            ]);
        }
    }

    protected function copyRiskLevels(Organization $organization): void
    {
        $riskLevelsToCopy = RiskLevel::where(['organization_id' => $this->demoOrganizationId])->get();
        foreach ($riskLevelsToCopy as $priority) {
            $organization->riskLevels()->create([
                'name' => $priority->name,
            ]);
        }
    }

    protected function copyRiskStatuses(Organization $organization): void
    {
        $riskStatusesToCopy = RiskStatus::where(['organization_id' => $this->demoOrganizationId])->get();
        foreach ($riskStatusesToCopy as $priority) {
            $organization->riskStatuses()->create([
                'name' => $priority->name,
            ]);
        }
    }

    protected function copyProbabilities(Organization $organization): void
    {
        $probabilitiesToCopy = Probability::where(['organization_id' => $this->demoOrganizationId])->get();
        foreach ($probabilitiesToCopy as $priority) {
            $organization->probabilities()->create([
                'name' => $priority->name,
            ]);
        }
    }

    protected function copyRoles(Organization $organization): void
    {
        $rolesToCopy = Role::where(['organization_id' => $this->demoOrganizationId])->get();
        foreach ($rolesToCopy as $role) {
            $organization->roles()->create([
                'name' => $role->name,
                'is_default' => $role->is_default,
            ]);
        }
    }

    protected function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (Organization::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
