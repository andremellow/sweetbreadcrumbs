<?php

use App\Actions\CreateOrganization;
use App\Actions\CreateProject;
use App\Models\Organization;
use App\Models\Priority;
use App\Models\Probability;
use App\Models\Release;
use App\Models\RiskLevel;
use App\Models\RiskStatus;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = app(CreateOrganization::class)($this->user, "New Organization Name");
});

it('creates a new project with required fields only', function () {
    $project = app(CreateProject::class) (
        $this->organization,
        "This is a test project",
        null, //$priorityId,
        null, //$toggleOnByReleaseId,
        null, //$releasePlan,
        null, //$technicalDocumentation,
        null, //$needsToStartBy,
        null, //$needsToDeployedBy,
    );  

    $project->refresh();

    expect($project->organization_id)->toBe($this->organization->id);
    expect($project->name)->toBe("This is a test project");
});

it('creates a new project with required with all fields', function () {
    $priority = $this->organization->priorities()->first();
    $release = Release::factory()->for($this->organization)->create(); 
    $project = app(CreateProject::class) (
        $this->organization,
        "This is a test project",
        $priority->id, //$priorityId,
        $release->id, //$toggleOnByReleaseId,
        "This is the release plan", //$releasePlan,
        "this is the technical documentation", //$technicalDocumentation,
        Carbon::now()->addDays(15), //$needsToStartBy,
        Carbon::now()->addMonth(3), //$needsToDeployedBy,
    );  

    $project->refresh();

    expect($project->organization_id)->toBe($this->organization->id);
    expect($project->name)->toBe("This is a test project");
    
    expect($project->priority_id)->toBe($priority->id);
    expect($project->toggle_on_by_release_id)->toBe($release->id);
    expect($project->release_plan)->toBe("This is the release plan");
    expect($project->technical_documentation)->toBe("this is the technical documentation");
    expect($project->needs_to_start_by->toDateString())->toBe(Carbon::now()->addDays(15)->toDateString());
    expect($project->needs_to_deployed_by->toDateString())->toBe(Carbon::now()->addMonth(3)->toDateString());
});


