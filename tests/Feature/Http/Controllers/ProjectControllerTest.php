<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Project\CreateProjectDTO;
use App\DTO\Project\DeleteProjectDTO;
use App\DTO\Project\UpdateProjectDTO;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use App\Services\OrganizationService;
use App\Services\ProjectService;
use Illuminate\Support\Facades\Session;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\getJson;
use function Pest\Laravel\patch;
use function Pest\Laravel\post;

beforeEach(function () {
    // Create User and Organization
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, 'New Organization');

    // ✅ Mock ProjectService and OrganizationService
    $this->mockProjectService = Mockery::mock(ProjectService::class);
    $this->mockOrganizationService = Mockery::mock(OrganizationService::class);

    // Authenticate User
    actingAs($this->user);
});

afterEach(function () {
    Mockery::close();
});

it('displays a list of projects', function () {
    // Create test projects
    Project::factory()->count(3)->for($this->organization)->create([
        'priority_id' => 7,
    ]);

    $response = getJson(route('projects.index', [
        'organization' => $this->organization->slug,
    ]));

    $response->assertInertia(fn (Assert $page) => $page
        ->component('projects/list-project')
        ->has('filters', fn (Assert $page) => $page->etc())
        ->has('sortable', fn (Assert $page) => $page
            ->where('sorted_by', 'name')
            ->where('sorted_direction', 'desc')
        )
        ->has('projects', fn (Assert $page) => $page
            ->has('data', 3)
            ->where('per_page', 15) // Assert pagination
            ->etc()
        )
        ->has('projects.data.0', fn (Assert $page) => $page
            ->has('id')
            ->has('name')
            ->has('priority.name')
            ->has('organization_id')
            ->has('priority_id')
            ->has('release_plan')
            ->has('technical_documentation')
            ->has('needs_to_start_by')
            ->has('needs_to_deployed_by')
            ->has('toggle_on_by_release_id')
            ->has('toggle_on_by_release')
            ->has('created_at')
        )
    );

    $response->assertStatus(200);
});

it('loads project to be edited', function () {
    $project = Project::factory()->for($this->organization)->create();

    $response = getJson(route('projects.edit', [
        'organization' => $this->organization->slug,
        'project' => $project->id,
    ]));

    $response->assertInertia(fn (Assert $page) => $page
        ->component('projects/create-project')
        ->has('project', fn (Assert $page) => $page
            ->has('id')
            ->has('name')
            ->has('priority_id')
            ->has('release_plan')
            ->has('technical_documentation')
            ->has('needs_to_start_by')
            ->has('needs_to_deployed_by')
            ->has('toggle_on_by_release_id')
            ->has('organization_id')
            ->has('created_at')
        )
        ->has('priorities')
        ->has('releases')
    );

    $response->assertStatus(200);
});

describe('Project modifications', function () {
    beforeEach(function () {
        $this->app->instance(ProjectService::class, $this->mockProjectService);
    });

    it('creates a new project and redirects', function () {
        $project = Project::factory()->create();
        $projectData = [
            'name' => 'New Project',
            'priority_id' => 1,
        ];

        // ✅ Expect DTO conversion & service method call
        $this->mockProjectService
            ->shouldReceive('create')
            ->once()
            ->with(
                User::class,
                CreateProjectDTO::class
            )
            ->andReturn($project);

        // Send request
        $response = post(route('projects.store', [
            'organization' => $this->organization->slug,
        ]), $projectData);

        $response->assertRedirect(route('projects.dashboard', [
            'organization' => $this->organization->slug,
            'project' => $project->id,

        ]));

        // ✅ Ensure success message is in session
        expect(Session::get('success'))->toBe('Project created');
    });

    it('returns validation errors when creating a project with invalid data', function () {
        $response = post(route('projects.store', [
            'organization' => $this->organization->slug,
        ]), []);

        $response->assertSessionHasErrors(['name']);
    });

    it('updates a project and redirects', function () {
        $project = Project::factory()->create();
        $updateData = [
            'name' => 'Updated Project Name',
            'priority_id' => 2,
        ];

        $this->mockProjectService
            ->shouldReceive('update')
            ->once()
            ->with(
                User::class,
                UpdateProjectDTO::class
            )
            ->andReturn($project);

        $response = patch(route('projects.update', [
            'organization' => $this->organization->slug,
            'project' => $project->id,
        ]), $updateData);

        $response->assertRedirect(route('projects.dashboard', [
            'organization' => $this->organization->slug,
            'project' => $project->id,
        ]));

        expect(Session::get('success'))->toBe('Project updated');
    });

    it('deletes a project and redirects', function () {
        $project = Project::factory()->create();
        $this->mockProjectService
            ->shouldReceive('delete')
            ->once()
            ->with($this->user, DeleteProjectDTO::class);

        $response = delete(route('projects.destroy', [
            'organization' => $this->organization->slug,
            'project' => $project->id,
        ]));

        $response->assertRedirect(route('projects.index', [
            'organization' => $this->organization->slug,
        ]));

        expect(Session::get('success'))->toBe('Project deleted');
    });
});
