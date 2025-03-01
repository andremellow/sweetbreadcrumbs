<?php

use App\Actions\CreateMeeting;
use App\Actions\UpdateMeeting;
use App\Enums\SortDirection;
use App\Models\Meeting;
use App\Models\Project;
use App\Services\MeetingService;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use function Pest\Laravel\mock;

beforeEach(function () {
    $this->mockCreateMeeting = Mockery::mock(CreateMeeting::class);
    $this->mockUpdateMeeting = Mockery::mock(UpdateMeeting::class);
    $this->service = new MeetingService($this->mockCreateMeeting, $this->mockUpdateMeeting);
});

afterEach(function () {
    Mockery::close();
});

it('creates a meeting using CreateMeeting action', function () {
    $project = Mockery::mock(Project::class);
    $name = 'Team Sync';
    $description = 'Weekly team meeting';
    $date = '2024-03-01 10:00:00';
    $carbonDate = Carbon::parse($date);

    $mockMeeting = Mockery::mock(Meeting::class);

    // Expect the CreateMeeting action to be called with these parameters
    $this->mockCreateMeeting
        ->shouldReceive('__invoke')
        ->once()
        ->with(Mockery::type(Project::class), 'Team Sync', 'Weekly team meeting', Mockery::type(Carbon::class))

        ->andReturn($mockMeeting);

    // Call the method
    $meeting = $this->service->create($project, $name, $description, $date);

    expect($meeting)->toBe($mockMeeting);
});

it('updates a meeting using UpdateMeeting action', function () {
    $project = Mockery::mock(Project::class);
    $meetingId = 1;
    $name = 'Updated Meeting';
    $description = 'Updated description';
    $date = '2024-03-02 15:30:00';
    $carbonDate = Carbon::parse($date);

    $mockMeeting = Mockery::mock(Meeting::class);

    // Expect the UpdateMeeting action to be called with these parameters
    $this->mockUpdateMeeting
        ->shouldReceive('__invoke')
        ->once()
        ->with(Mockery::type(Project::class), 1, 'Updated Meeting', 'Updated description', Mockery::type(Carbon::class))

        ->andReturn($mockMeeting);

    // Call the method
    $meeting = $this->service->update($project, $meetingId, $name, $description, $date);

    expect($meeting)->toBe($mockMeeting);
});

it('parses date string to Carbon instance', function () {
    $method = new ReflectionMethod(MeetingService::class, 'maybeParseToCarbon');
    $method->setAccessible(true);

    $carbonDate = $method->invoke($this->service, '2024-03-01 10:00:00');

    expect($carbonDate)->toBeInstanceOf(Carbon::class);
    expect($carbonDate->toDateTimeString())->toBe('2024-03-01 10:00:00');
});

it('does not modify existing Carbon instance', function () {
    $method = new ReflectionMethod(MeetingService::class, 'maybeParseToCarbon');
    $method->setAccessible(true);

    $carbonInstance = Carbon::now();
    $result = $method->invoke($this->service, $carbonInstance);

    expect($result)->toBe($carbonInstance);
});

it('lists meetings with sorting and filtering', function () {
    $project = Mockery::mock(Project::class);
    $queryBuilder = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
    $paginator = Mockery::mock(LengthAwarePaginator::class);

    $project->shouldReceive('meetings')->once()->andReturn($queryBuilder);
    $queryBuilder->shouldReceive('when')->with('Test')->andReturnSelf();
    $queryBuilder->shouldReceive('orderBy')->with('name', 'asc')->andReturnSelf();
    $queryBuilder->shouldReceive('paginate')->andReturn($paginator);
    $paginator->shouldReceive('appends')->andReturnSelf();

    $result = $this->service->list($project, 'Test', 'name', SortDirection::ASC);

    expect($result)->toBe($paginator);
});
