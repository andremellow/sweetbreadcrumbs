<?php

use App\Actions\CreateOrganization;
use App\Actions\UpdateProject;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Release;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = app(CreateOrganization::class)($this->user, 'New Organization Name');
    $this->project = Project::factory()->for($this->organization)->create();
});

it('updates a project with required fields only', function () {
    $updatedProject = app(UpdateProject::class)(
        $this->organization,
        $this->project->id,
        'Updated Project Name',
        null, // Priority ID
        null, // Toggle On By Release ID
        null, // Release Plan
        null, // Technical Documentation
        null, // Needs to Start By
        null  // Needs to Be Deployed By
    );

    $updatedProject->refresh();

    expect($updatedProject->id)->toBe($this->project->id);
    expect($updatedProject->organization_id)->toBe($this->organization->id);
    expect($updatedProject->name)->toBe('Updated Project Name');
    expect($updatedProject->release_plan)->toBeNull();
    expect($updatedProject->technical_documentation)->toBeNull();
    expect($updatedProject->priority_id)->toBeNull();
    expect($updatedProject->toggle_on_by_release_id)->toBeNull();
    expect($updatedProject->release_plan)->toBeNull();
});

it('updates a project with all fields', function () {
    $priority = $this->organization->priorities()->first() ?? Priority::factory()->for($this->organization)->create();
    $release = Release::factory()->for($this->organization)->create();

    $updatedProject = app(UpdateProject::class)(
        $this->organization,
        $this->project->id,
        'Updated Project Name',
        $priority->id, // Priority ID
        $release->id, // Toggle On By Release ID
        'Updated Release Plan', // Release Plan
        'Updated Technical Documentation', // Technical Documentation
        Carbon::now()->addDays(20), // Needs to Start By
        Carbon::now()->addMonth(4)  // Needs to Be Deployed By
    );

    $updatedProject->refresh();

    expect($updatedProject->id)->toBe($this->project->id);
    expect($updatedProject->organization_id)->toBe($this->organization->id);
    expect($updatedProject->name)->toBe('Updated Project Name');

    expect($updatedProject->priority_id)->toBe($priority->id);
    expect($updatedProject->toggle_on_by_release_id)->toBe($release->id);
    expect($updatedProject->release_plan)->toBe('Updated Release Plan');
    expect($updatedProject->technical_documentation)->toBe('Updated Technical Documentation');
    expect($updatedProject->needs_to_start_by->toDateString())->toBe(Carbon::now()->addDays(20)->toDateString());
    expect($updatedProject->needs_to_deployed_by->toDateString())->toBe(Carbon::now()->addMonth(4)->toDateString());
});
