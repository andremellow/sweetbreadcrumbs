<?php

use App\Actions\Organization\CreateOrganization;
use App\Actions\Project\CreateProject;
use App\DTO\Project\CreateProjectDTO;
use App\Models\Release;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = app(CreateOrganization::class)($this->user, 'New Organization Name');
});

it('creates a new project with required fields only', function () {
    $project = app(CreateProject::class)(
        CreateProjectDTO::from([
            'organization' => $this->organization,
            ...[
                'name' => 'This is a test project',
            ],
        ])
    );

    $project->refresh();

    expect($project->organization_id)->toBe($this->organization->id);
    expect($project->name)->toBe('This is a test project');
    expect($project->release_plan)->toBeNull();
    expect($project->technical_documentation)->toBeNull();
    expect($project->priority_id)->toBeNull();
    expect($project->toggle_on_by_release_id)->toBeNull();
    expect($project->release_plan)->toBeNull();

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
                'toggle_on_by_release_id' => $release->id,
                'release_plan' => 'This is the release plan',
                'technical_documentation' => 'this is the technical documentation',
                'needs_to_start_by' => Carbon::now()->addDays(15)->format('Y/m/d'),
                'needs_to_deployed_by' => Carbon::now()->addMonth(3)->format('Y/m/d'),
            ],
        ])
    );

    $project->refresh();

    expect($project->organization_id)->toBe($this->organization->id);
    expect($project->name)->toBe('This is a test project');

    expect($project->priority_id)->toBe($priority->id);
    expect($project->toggle_on_by_release_id)->toBe($release->id);
    expect($project->release_plan)->toBe('This is the release plan');
    expect($project->technical_documentation)->toBe('this is the technical documentation');
    expect($project->needs_to_start_by->toDateString())->toBe(Carbon::now()->addDays(15)->toDateString());
    expect($project->needs_to_deployed_by->toDateString())->toBe(Carbon::now()->addMonth(3)->toDateString());
});
