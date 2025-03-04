<?php

use App\Actions\Organization\CreateOrganization;
use App\Actions\Project\UpdateProject;
use App\DTO\Organization\CreateOrganizationDTO;
use App\DTO\Project\UpdateProjectDTO;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Release;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));
    $this->project = Project::factory()->for($this->organization)->create();
});

it('updates a project with required fields only', function () {
    $updatedProject = app(UpdateProject::class)(
        UpdateProjectDTO::from([
            'organization' => $this->organization,
            'project_id' => $this->project->id,
            'name' => 'Updated Project Name',
        ]));

    $updatedProject->refresh();

    expect($updatedProject->id)->toBe($this->project->id);
    expect($updatedProject->organization_id)->toBe($this->organization->id);
    expect($updatedProject->name)->toBe('Updated Project Name');
    expect($updatedProject->release_plan)->toBeNull();
    expect($updatedProject->technical_documentation)->toBeNull();
    expect($updatedProject->priority_id)->toBeNull();
    expect($updatedProject->release_plan)->toBeNull();
});

it('updates a project with all fields', function () {
    $priority = $this->organization->priorities()->first() ?? Priority::factory()->for($this->organization)->create();
    $release = Release::factory()->for($this->organization)->create();

    $updatedProject = app(UpdateProject::class)(
        UpdateProjectDTO::from([
            'organization' => $this->organization,
            'project_id' => $this->project->id,
            'name' => 'Updated Project Name',
            'priority_id' => $priority->id, // Priority ID
            'release_plan' => 'Updated Release Plan', // Release Plan
            'technical_documentation' => 'Updated Technical Documentation', // Technical Documentation
            'needs_to_start_by' => Carbon::now()->addDays(20)->format(config('app.save_date_format')), // Needs to Start By
            'needs_to_deployed_by' => Carbon::now()->addMonth(4)->format(config('app.save_date_format')), // Needs to Be Deployed By
        ])
    );

    $updatedProject->refresh();

    expect($updatedProject->id)->toBe($this->project->id);
    expect($updatedProject->organization_id)->toBe($this->organization->id);
    expect($updatedProject->name)->toBe('Updated Project Name');
    expect($updatedProject->priority_id)->toBe($priority->id);
    expect($updatedProject->release_plan)->toBe('Updated Release Plan');
    expect($updatedProject->technical_documentation)->toBe('Updated Technical Documentation');
    expect($updatedProject->needs_to_start_by->toDateString())->toBe(Carbon::now()->addDays(20)->toDateString());
    expect($updatedProject->needs_to_deployed_by->toDateString())->toBe(Carbon::now()->addMonth(4)->toDateString());
});
