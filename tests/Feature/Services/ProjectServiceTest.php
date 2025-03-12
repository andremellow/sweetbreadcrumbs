<?php

use App\Actions\Organization\CreateOrganization;
use App\Actions\Project\CreateProject;
use App\Actions\Project\DeleteProject;
use App\Actions\Project\UpdateProject;
use App\DTO\Organization\CreateOrganizationDTO;
use App\DTO\Project\CreateProjectDTO;
use App\DTO\Project\DeleteProjectDTO;
use App\DTO\Project\UpdateProjectDTO;
use App\Enums\SortDirection;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

covers(ProjectService::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    // Create test organization
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('new Organization'));

    // Mock dependencies
    /** @var CreateProject */
    $this->mockCreateProject = Mockery::mock(CreateProject::class);

    /** @var UpdateProject */
    $this->mockUpdateProject = Mockery::mock(UpdateProject::class);

    /** @var DeleteProject */
    $this->mockDeleteProject = Mockery::mock(DeleteProject::class);

    // Instantiate the service with mocks
    $this->service = new ProjectService($this->mockCreateProject, $this->mockUpdateProject, $this->mockDeleteProject);
});

afterEach(function () {
    Mockery::close();
});

it('creates a project using CreateProject action', function () {
    $dto = CreateProjectDTO::from([
        'organization' => $this->organization,
        ...[
            'name' => 'New Project',
            'priority_id' => 1,
        ],
    ]);

    $mockProject = Mockery::mock(Project::class);

    // Expect CreateProject action to be called
    $this->mockCreateProject
        ->shouldReceive('__invoke')
        ->once()
        ->with(
            $dto
        )
        ->andReturn($mockProject);

    // Call the method
    $project = $this->service->create(
        $this->user,
        $dto
    );

    expect($project)->toBe($mockProject);
});

it('updates a project using UpdateProject action', function () {
    // Prepare test data

    $dto = UpdateProjectDTO::from([
        'organization' => $this->organization,
        'project_id' => 1,
        'priority_id' => 1,
        'name' => 'Updated Project',
    ]);

    $mockProject = Mockery::mock(Project::class);

    // Expect UpdateProject action to be called
    $this->mockUpdateProject
        ->shouldReceive('__invoke')
        ->once()
        ->with(
            $dto
        )
        ->andReturn($mockProject);

    // Call the method
    $project = $this->service->update(
        $this->user,
        $dto
    );

    expect($project)->toBe($mockProject);
});

it('deletes a project using DeleteProject action', function () {
    /** @var Project */
    $mockProject = Mockery::mock(Project::class);

    $dto = new DeleteProjectDTO($mockProject);

    $mockProject = Mockery::mock(Project::class);

    // Expect DeleteProject action to be called
    $this->mockDeleteProject
        ->shouldReceive('__invoke')
        ->once()
        ->with(
            $dto
        );

    // Call the method
    $this->service->delete(
        $this->user,
        $dto
    );
});

describe('list projects', function () {

    beforeEach(function () {
        // 6	 = Highest
        // 7	 = High
        // 8	 = Midium
        // 9	 = Low
        // 10 = Lowest

        Project::factory()->for($this->organization)->create(['name' => 'Project A', 'priority_id' => 8, 'created_at' => Carbon::now()->addDay(11)]);
        Project::factory()->for($this->organization)->create(['name' => 'Project A.1', 'priority_id' => 8, 'created_at' => Carbon::now()->addDay(10)]);
        Project::factory()->for($this->organization)->create(['name' => 'Project B', 'priority_id' => 8, 'created_at' => Carbon::now()->addDay(9)]);
        Project::factory()->for($this->organization)->create(['name' => 'Project B.2', 'priority_id' => 7, 'created_at' => Carbon::now()->addDay(8)]);
        Project::factory()->for($this->organization)->create(['name' => 'Project C', 'priority_id' => 7, 'created_at' => Carbon::now()->addDay(7)]);
        Project::factory()->for($this->organization)->create(['name' => 'Project C.3', 'priority_id' => 7, 'created_at' => Carbon::now()->addDay(6)]);
        Project::factory()->for($this->organization)->create(['name' => 'Project D', 'priority_id' => 6, 'created_at' => Carbon::now()->addDay(5)]);
        Project::factory()->for($this->organization)->create(['name' => 'Project E', 'priority_id' => 6, 'created_at' => Carbon::now()->addDay(4)]);
        Project::factory()->for($this->organization)->create(['name' => 'Project G', 'priority_id' => 8, 'created_at' => Carbon::now()->addDay(3)]);
        Project::factory()->for($this->organization)->create(['name' => 'Project H', 'priority_id' => 7, 'created_at' => Carbon::now()->addDay(2)]);
        Project::factory()->for($this->organization)->create(['name' => 'Project H.1', 'priority_id' => 8, 'created_at' => Carbon::now()->addDay(1)]);
    });

    it('lists projects with default sorting', function () {
        $projects = $this->service->list($this->organization, null, null);

        expect($projects)->toBeInstanceOf(LengthAwarePaginator::class);
        expect($projects->total())->toBe(11);
        expect($projects[0]->name)->toBe('Project A');
        expect($projects[1]->name)->toBe('Project A.1');
        expect($projects[2]->name)->toBe('Project B');
    });

    it('lists projects sorted by priority', function () {
        $projects = $this->service->list(
            $this->organization,
            null,
            null,
            'priority',
            SortDirection::ASC
        );

        expect($projects->total())->toBe(11);
        expect($projects)->toBeInstanceOf(LengthAwarePaginator::class);
        expect($projects[0]->priority_id)->toBe(6);
        expect($projects[1]->priority_id)->toBe(6);
        expect($projects[2]->priority_id)->toBe(7);
    });

    it('lists projects sorted by date', function () {
        $projects = $this->service->list(
            $this->organization,
            null,
            null,
            'date',
            SortDirection::ASC
        );

        expect($projects->total())->toBe(11);
        expect($projects)->toBeInstanceOf(LengthAwarePaginator::class);
        expect($projects[0]->name)->toBe('Project H.1');
        expect($projects[1]->name)->toBe('Project H');
        expect($projects[2]->name)->toBe('Project G');
    });

    it('filters projects by contain name', function () {
        $projects = $this->service->list($this->organization, 'ct B', null);

        expect($projects)->toHaveCount(2);
        expect($projects[0]->name)->toBe('Project B');
        expect($projects[1]->name)->toBe('Project B.2');
    });

    it('filters projects by maching name', function () {
        $projects = $this->service->list($this->organization, 'Project B.2', null);

        expect($projects)->toHaveCount(1);
        expect($projects[0]->name)->toBe('Project B.2');
    });

    it('filters projects by priority', function () {
        $projects = $this->service->list($this->organization, null, 7);

        expect($projects)->toBeInstanceOf(LengthAwarePaginator::class);
        expect($projects)->toHaveCount(4);
        expect($projects[0]->priority_id)->toBe(7);
        expect($projects[1]->priority_id)->toBe(7);
        expect($projects[2]->priority_id)->toBe(7);
        expect($projects[3]->priority_id)->toBe(7);
    });

    it('paginates projects correctly', function () {
        Config::set('app.pagination_items', 2);
        Request::merge(['page' => 6]);

        $projects = $this->service->list($this->organization, null, null);

        expect($projects)->toBeInstanceOf(LengthAwarePaginator::class);
        expect($projects->total())->toBe(11);
        expect($projects->perPage())->toBe(2);
        expect($projects->lastPage())->toBe(6);
        expect($projects->currentPage())->toBe(6);
        expect($projects->hasMorePages())->toBeFalse();
    });
});
