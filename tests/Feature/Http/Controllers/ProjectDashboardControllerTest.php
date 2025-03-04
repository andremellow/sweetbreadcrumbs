<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

beforeEach(function () {
    // Create User and Organization
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New organization'));
    $this->project = Project::factory()->for($this->organization)->create(['priority_id' => 5]);

    // Authenticate User
    actingAs($this->user);
});

afterEach(function () {
    Mockery::close();
});

it('displays a project dashboard', function () {
    // Create test meetings
    Meeting::factory()->count(3)->for($this->project)->create();

    $response = getJson(route('projects.dashboard', [
        'organization' => $this->organization->slug,
        'project' => $this->project->id,
    ]));

    // GO here is to test if the json structure match what it needs to be.
    $response->assertInertia(fn (Assert $page) => $page
        ->component('projects/dashboard')
        ->has('project', fn (Assert $page) => $page
            ->has('id')
            ->has('name')
            ->has('priority', fn (Assert $page) => $page
                ->has('name')
                ->etc()
            )
            ->has('organization_id')
            ->has('priority_id')
            ->has('release_plan')
            ->has('technical_documentation')
            ->has('needs_to_start_by')
            ->has('needs_to_deployed_by')
            ->has('created_at')
        )
    );

    $response->assertStatus(200);
});
