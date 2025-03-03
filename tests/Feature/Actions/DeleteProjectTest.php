<?php

use App\Actions\Organization\CreateOrganization;
use App\Actions\Project\DeleteProject;
use App\DTO\Project\DeleteProjectDTO;
use App\Models\Project;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = app(CreateOrganization::class)($this->user, 'New Organization Name');
});

it('Soft deletes a meeting', function () {
    $project = Project::factory()->for($this->organization)->create();
    expect($project->deleted_at)->toBeNull();

    app(DeleteProject::class)(
        new DeleteProjectDTO($project)
    );

    $project->refresh();

    expect($project->deleted_at)->not->toBeNull();
});
