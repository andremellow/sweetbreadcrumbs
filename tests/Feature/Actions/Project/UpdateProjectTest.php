<?php

use App\Actions\Organization\CreateOrganization;
use App\Actions\Project\UpdateProject;
use App\DTO\Organization\CreateOrganizationDTO;
use App\DTO\Project\UpdateProjectDTO;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Release;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));
    $this->project = Project::factory()->for($this->organization)->withPriority($this->organization)->create();
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
        ])
    );

    $updatedProject->refresh();

    expect($updatedProject->id)->toBe($this->project->id);
    expect($updatedProject->organization_id)->toBe($this->organization->id);
    expect($updatedProject->name)->toBe('Updated Project Name');
    expect($updatedProject->priority_id)->toBe($priority->id);
});
