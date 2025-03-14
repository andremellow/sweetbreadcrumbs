<?php

use App\Actions\Organization\CreateOrganization;
use App\Actions\Task\CloseTask;
use App\Actions\Task\CreateTask;
use App\Actions\Task\OpenTask;
use App\DTO\Organization\CreateOrganizationDTO;
use App\DTO\Task\CloseTaskDTO;
use App\DTO\Task\CreateTaskDTO;
use App\DTO\Task\OpenTaskDTO;
use App\Enums\SortDirection;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\OrganizationService;
use App\Services\TaskService;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

covers(TaskService::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));
    $this->project = Project::factory()->for($this->organization)->withPriority($this->organization)->create();
    /** @var CreateTask */
    $this->mockCreateTask = Mockery::mock(CreateTask::class);
    // /** @var UpdateMeeting */
    // $this->mockUpdateMeeting = Mockery::mock(UpdateMeeting::class);
    // /** @var DeleteMeeting */
    // $this->mockDeleteMeeting = Mockery::mock(DeleteMeeting::class);

    /** @var CloseTask */
    $this->mockCloseTask = Mockery::mock(CloseTask::class);

    /** @var CreateTask */
    $this->mockCreateTask = Mockery::mock(CreateTask::class);

    /** @var OpenTask */
    $this->mockOpenTask = Mockery::mock(OpenTask::class);
    $this->service = new TaskService(organizationService: app(OrganizationService::class), closeTask: $this->mockCloseTask, openTask: $this->mockOpenTask, createTask: $this->mockCreateTask);
});

afterEach(function () {
    Mockery::close();
});

// it('creates a task using CreateTask action', function () {
//     /** @var Project $project */
//     $project = Mockery::mock(Project::class);
//     $name = 'Team Sync';
//     $description = 'Weekly team task';
//     $date = '2024-03-01 10:00:00';
//     $carbonDate = Carbon::parse($date);

//     $mockMeeting = Mockery::mock(Meeting::class);

//     // Expect the CreateTask action to be called with these parameters
//     $this->mockCreateTask
//         ->shouldReceive('__invoke')
//         ->once()
//         ->with(CreateTaskDTO::class)
//         ->andReturn($mockMeeting);

//     // Call the method
//     $task = $this->service->create(
//         $this->user,
//         new CreateTaskDTO(
//             $project, $name, $description, $carbonDate
//         )
//     );

//     expect($task)->toBe($mockMeeting);
// });

// it('updates a task using UpdateMeeting action', function () {
//     /** @var Project */
//     $project = Mockery::mock(Project::class);

//     $dto = new UpdateMeetingDTO(
//         $project,
//         1,
//         'Updated Meeting',
//         'Updated description',
//         Carbon::now()
//     );

//     $mockMeeting = Mockery::mock(Meeting::class);

//     // Expect the UpdateMeeting action to be called with these parameters
//     $this->mockUpdateMeeting
//         ->shouldReceive('__invoke')
//         ->once()
//         ->with($dto)

//         ->andReturn($mockMeeting);

//     // Call the method
//     $task = $this->service->update($this->user, $dto);

//     expect($task)->toBe($mockMeeting);
// });

// it('deletes a task using UpdateMeeting action', function () {
//     $project = Mockery::mock(Project::class);
//     /** @var Meeting */
//     $mockMeeting = Mockery::mock(Meeting::class);
//     $dto = new DeleteMeetingDTO($mockMeeting);
//     // Expect the UpdateMeeting action to be called with these parameters
//     $this->mockDeleteMeeting
//         ->shouldReceive('__invoke')
//         ->once()
//         ->with($dto);

//     // Call the method
//     $this->service->delete(
//         $this->user,
//         $dto
//     );

// });

it('opens a task using OpenTask action', function () {
    $dto = new OpenTaskDTO(user: $this->user, task_id: 10);
    // Expect the OpenTask action to be called with these parameters
    $this->mockOpenTask
        ->shouldReceive('__invoke')
        ->once()
        ->with($dto);

    // Call the method
    $this->service->open(
        $dto
    );
});

it('closes a task using Close action', function () {
    $dto = new CloseTaskDTO(user: $this->user, task_id: 10);
    // Expect the Close action to be called with these parameters
    $this->mockCloseTask
        ->shouldReceive('__invoke')
        ->once()
        ->with($dto);

    // Call the method
    $this->service->close(
        $dto
    );
});

it('creates a task using Create action', function () {
    $dto = new CreateTaskDTO(
        user: $this->user,
        project: $this->project,
        name: 'My task',
        priority_id: 6
    );

    // Expect the Close action to be called with these parameters
    $this->mockCreateTask
        ->shouldReceive('__invoke')
        ->once()
        ->with($dto);

    // Call the method
    $this->service->create(
        $dto
    );
});

describe('list tasks', function () {
    beforeEach(function () {
        Task::factory()->for($this->project, 'taskable')->create(['name' => 'Stand up 1',           'description' => 'Description for Stand up 1',          'priority_id' => 6,  /* Highest */ 'due_date' => Carbon::now()->addDays(9), 'completed_at' => Carbon::now()->addDays(9)]);
        Task::factory()->for($this->project, 'taskable')->create(['name' => 'Stand up 2',           'description' => 'Description for Stand up 2',          'priority_id' => 6,  /* Highest */ 'due_date' => Carbon::now()->addDays(8)]);
        Task::factory()->for($this->project, 'taskable')->create(['name' => 'Stand up 3',           'description' => 'Description for Stand up 3',          'priority_id' => 7,  /* High */ 'due_date' => Carbon::now()->addDays(7)]);
        Task::factory()->for($this->project, 'taskable')->create(['name' => 'TD descusion 1',       'description' => 'Description for TD descusion 1',      'priority_id' => 7,  /* High */ 'due_date' => Carbon::now()->addDays(6)]);
        Task::factory()->for($this->project, 'taskable')->create(['name' => 'TD descusion 2',       'description' => 'Description for TD descusion 2',      'priority_id' => 8,  /* Midium */ 'due_date' => Carbon::now()->addDays(5)]);
        Task::factory()->for($this->project, 'taskable')->create(['name' => 'TD descusion 3',       'description' => 'Description for TD descusion 3',      'priority_id' => 6,  /* Highest */ 'due_date' => Carbon::now()->addDays(4)]);
        Task::factory()->for($this->project, 'taskable')->create(['name' => 'Sprint planing 1',     'description' => 'Description for Sprint planing 1',    'priority_id' => 9,  /* Low */ 'due_date' => Carbon::now()->addDays(3)]);
        Task::factory()->for($this->project, 'taskable')->create(['name' => 'Sprint planing 2',     'description' => 'Description for Sprint planing 2',    'priority_id' => 10, /* Lowest */ 'due_date' => Carbon::now()->addDays(-1)]);
        Task::factory()->for($this->project, 'taskable')->create(['name' => 'Sprint planing 3',     'description' => 'Description for Sprint planing 3',    'priority_id' => 10, /* Lowest */ 'due_date' => Carbon::now()->addDays(-2), 'completed_at' => Carbon::now()->addDays(1)]);
    });

    it('lists tasks default sort by name if invalid argument is given', function () {
        $tasks = $this->service->list(
            project: $this->project,
            sortBy: 'any_invalid_sort_fields',
            sortDirection: SortDirection::ASC
        );

        expect($tasks)->toHaveCount(9);
        expect($tasks[0]->name)->toBe('Sprint planing 1');
    });

    it('lists tasks with default sorting - due date', function () {
        $tasks = $this->service->list(
            $this->project
        );

        expect($tasks)->toHaveCount(9);
        expect($tasks[0]->name)->toBe('Stand up 1');
        expect($tasks[1]->name)->toBe('Stand up 2');
        expect($tasks[2]->name)->toBe('Stand up 3');
        expect($tasks[3]->name)->toBe('TD descusion 1');
    });

    it('lists tasks with default ask sorting', function () {
        $tasks = $this->service->list(
            project: $this->project,
            sortBy: 'due_date',
            sortDirection: SortDirection::ASC
        );

        expect($tasks[0]->name)->toBe('Sprint planing 3');
        expect($tasks[1]->name)->toBe('Sprint planing 2');
        expect($tasks[2]->name)->toBe('Sprint planing 1');
        expect($tasks[3]->name)->toBe('TD descusion 3');
    });

    it('lists tasks with date range sorting', function () {
        $tasks = $this->service->list(
            project: $this->project,
            dateStart: Carbon::now()->addDays(-1),
            dateEnd: Carbon::now()->addDays(5),
            sortBy: 'due_date',
            sortDirection: SortDirection::DESC
        );

        expect($tasks)->toHaveCount(4);
        expect($tasks[0]->name)->toBe('TD descusion 2');
        expect($tasks[1]->name)->toBe('TD descusion 3');
        expect($tasks[2]->name)->toBe('Sprint planing 1');
        expect($tasks[3]->name)->toBe('Sprint planing 2');
    });

    it('lists tasks with priority sorting', function () {
        $tasks = $this->service->list(
            project: $this->project,
            sortBy: 'priority',
            sortDirection: SortDirection::ASC
        );

        expect($tasks)->toHaveCount(9);

        expect($tasks[0]->name)->toBe('Stand up 1');
        expect($tasks[1]->name)->toBe('Stand up 2');
        expect($tasks[2]->name)->toBe('TD descusion 3');
        expect($tasks[3]->name)->toBe('Stand up 3');
        expect($tasks[5]->name)->toBe('TD descusion 2');
        expect($tasks[6]->name)->toBe('Sprint planing 1');
        expect($tasks[7]->name)->toBe('Sprint planing 2');
    });

    it('lists late tasks', function () {
        $tasks = $this->service->list(
            project: $this->project,
            dateEnd: Carbon::now(),
            status: 'open',
            sortBy: 'due_date',
            sortDirection: SortDirection::DESC
        );

        expect($tasks)->toHaveCount(1);
        expect($tasks[0]->name)->toBe('Sprint planing 2');
    });

    it('lists open tasks', function () {
        $tasks = $this->service->list(
            project: $this->project,
            status: 'open',
            sortBy: 'due_date',
            sortDirection: SortDirection::DESC
        );

        expect($tasks)->toHaveCount(7);
        expect($tasks[0]->name)->toBe('Stand up 2');
        expect($tasks[1]->name)->toBe('Stand up 3');
        expect($tasks[2]->name)->toBe('TD descusion 1');
    });

    it('lists closed tasks', function () {
        $tasks = $this->service->list(
            project: $this->project,
            status: 'closed',
            sortBy: 'due_date',
            sortDirection: SortDirection::DESC
        );

        expect($tasks)->toHaveCount(2);
        expect($tasks[0]->name)->toBe('Stand up 1');
        expect($tasks[1]->name)->toBe('Sprint planing 3');
    });

    it('lists tasks by priorities', function () {
        $tasks = $this->service->list(
            project: $this->project,
            priorityId: 6,
            sortDirection: SortDirection::DESC
        );

        expect($tasks)->toHaveCount(3);
        expect($tasks[0]->name)->toBe('Stand up 1');
        expect($tasks[1]->name)->toBe('Stand up 2');
        expect($tasks[2]->name)->toBe('TD descusion 3');
    });

    it('lists tasks with partial name', function () {
        $tasks = $this->service->list(
            project: $this->project,
            search: 'TD desc'
        );

        expect($tasks)->toHaveCount(3);
        expect($tasks[0]->name)->toBe('TD descusion 1');
        expect($tasks[1]->name)->toBe('TD descusion 2');
        expect($tasks[2]->name)->toBe('TD descusion 3');
    });

    it('lists tasks with matching name', function () {
        $tasks = $this->service->list(
            project: $this->project,
            search: 'Stand up 3'
        );

        expect($tasks)->toHaveCount(1);
        expect($tasks[0]->name)->toBe('Stand up 3');
    });

    it('paginates', function () {
        Config::set('app.pagination_items', 2);
        Request::merge(['page' => 5]);

        $tasks = $this->service->list(
            $this->project
        );

        expect($tasks)->toHaveCount(1);
        expect($tasks)->toBeInstanceOf(LengthAwarePaginator::class);
        expect($tasks->total())->toBe(9);
        expect($tasks->perPage())->toBe(config('app.pagination_items'));
        expect($tasks->lastPage())->toBe(5);
        expect($tasks->currentPage())->toBe(5);
        expect($tasks->hasMorePages())->toBeFalse();
        expect($tasks->previousPageUrl())->not->toBeNull();
        expect($tasks->nextPageUrl())->toBeNull();
        expect($tasks->previousPageUrl())->not->toBeNull();
    });

});
