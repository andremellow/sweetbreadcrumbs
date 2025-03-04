<?php

namespace App\Actions\Organization;

use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\Organization;
use App\Models\Priority;
use App\Models\Probability;
use App\Models\RiskLevel;
use App\Models\RiskStatus;
use App\Models\User;
use Illuminate\Support\Str;

class CreateOrganization
{
    protected $demoOrganizationId;

    /**
     * Creates new organization.
     *
     * @param User                  $user
     * @param CreateOrganizationDTO $createOrganizationDTO
     *
     * @return Organization
     */
    public function __invoke(User $user, CreateOrganizationDTO $createOrganizationDTO)
    {
        $this->demoOrganizationId = config('app.demo_organization_id');
        $organization = Organization::create([
            'name' => $createOrganizationDTO->name,
            'slug' => $this->generateUniqueSlug($createOrganizationDTO->name),
        ]);

        $this->attachUser($organization, $user);

        $this->copyPriorities($organization);
        $this->copyRiskLevels($organization);
        $this->copyRiskStatuses($organization);
        $this->copyProbabilities($organization);

        return $organization;
    }

    protected function copyPriorities(Organization $organization)
    {
        $prioritiesToCopy = Priority::where(['organization_id' => $this->demoOrganizationId])->get();
        foreach ($prioritiesToCopy as $priority) {
            $organization->priorities()->create([
                'name' => $priority->name,
                'order' => $priority->order,
            ]);
        }
    }

    protected function copyRiskLevels(Organization $organization)
    {
        $riskLevelsToCopy = RiskLevel::where(['organization_id' => $this->demoOrganizationId])->get();
        foreach ($riskLevelsToCopy as $priority) {
            $organization->riskLevels()->create([
                'name' => $priority->name,
            ]);
        }
    }

    protected function copyRiskStatuses(Organization $organization)
    {
        $riskStatusesToCopy = RiskStatus::where(['organization_id' => $this->demoOrganizationId])->get();
        foreach ($riskStatusesToCopy as $priority) {
            $organization->riskStatuses()->create([
                'name' => $priority->name,
            ]);
        }
    }

    protected function copyProbabilities(Organization $organization)
    {
        $probabilitiesToCopy = Probability::where(['organization_id' => $this->demoOrganizationId])->get();
        foreach ($probabilitiesToCopy as $priority) {
            $organization->probabilities()->create([
                'name' => $priority->name,
            ]);
        }
    }

    protected function attachUser(Organization $organization, User $user): void
    {
        $organization->users()->attach($user);
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
