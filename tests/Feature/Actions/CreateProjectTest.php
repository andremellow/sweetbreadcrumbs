<?php

use App\Actions\Organization\CreateOrganization;
use App\Actions\Project\CreateProject;
use App\DTO\Organization\CreateOrganizationDTO;
use App\DTO\Project\CreateProjectDTO;
use App\Models\Release;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));
});

it('creates a new project with required with all fields', function () {
    $priority = $this->organization->priorities()->first();
    $release = Release::factory()->for($this->organization)->create();

    $project = app(CreateProject::class)(
        CreateProjectDTO::from([
            'organization' => $this->organization,
            ...[
                'name' => 'This is a test project',
                'priority_id' => $priority->id,
            ],
        ])
    );

    $project->refresh();

    expect($project->organization_id)->toBe($this->organization->id);
    expect($project->name)->toBe('This is a test project');
    expect($project->priority_id)->toBe($priority->id);
});
