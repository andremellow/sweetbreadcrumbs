<?php

use App\Actions\CreateMeeting;
use App\Actions\CreateOrganization;
use App\Actions\UpdateMeeting;
use App\Enums\SortDirection;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\User;
use App\Services\MeetingService;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Mockery;

covers(MeetingService::class);
 

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = app(CreateOrganization::class)($this->user, 'New Organization Name');
    $this->project = Project::factory()->for($this->organization)->create();
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

describe('list meetings', function () {
    beforeEach(function () {
        Meeting::factory()->for($this->project)->create(['name' => 'Stand up 1', 'description' => 'Description for Stand up 1', 'date' => Carbon::now()->addDays(9)]);
        Meeting::factory()->for($this->project)->create(['name' => 'Stand up 2', 'description' => 'Description for Stand up 2', 'date' => Carbon::now()->addDays(8)]);
        Meeting::factory()->for($this->project)->create(['name' => 'Stand up 3', 'description' => 'Description for Stand up 3', 'date' => Carbon::now()->addDays(7)]);
        Meeting::factory()->for($this->project)->create(['name' => 'TD descusion 1', 'description' => 'Description for TD descusion 1', 'date' => Carbon::now()->addDays(6)]);
        Meeting::factory()->for($this->project)->create(['name' => 'TD descusion 2', 'description' => 'Description for TD descusion 2', 'date' => Carbon::now()->addDays(5)]);
        Meeting::factory()->for($this->project)->create(['name' => 'TD descusion 3', 'description' => 'Description for TD descusion 3', 'date' => Carbon::now()->addDays(4)]);
        Meeting::factory()->for($this->project)->create(['name' => 'Sprint planing 1', 'description' => 'Description for Sprint planing 1', 'date' => Carbon::now()->addDays(3)]);
        Meeting::factory()->for($this->project)->create(['name' => 'Sprint planing 2', 'description' => 'Description for Sprint planing 2', 'date' => Carbon::now()->addDays(2)]);
        Meeting::factory()->for($this->project)->create(['name' => 'Sprint planing 3', 'description' => 'Description for Sprint planing 3', 'date' => Carbon::now()->addDays(1)]);
    });

    it('lists meetings with default sorting', function () {
        $meetings = $this->service->list(
            $this->project
        );

        expect($meetings)->toHaveCount(9);
        expect($meetings[0]->name)->toBe('Sprint planing 1');
        expect($meetings[1]->name)->toBe('Sprint planing 2');
        expect($meetings[2]->name)->toBe('Sprint planing 3');
        expect($meetings[3]->name)->toBe('Stand up 1');
    });

    it('lists meetings with default desc sorting', function () {
        $meetings = $this->service->list(
            $this->project,
            null,
            'name',
            SortDirection::DESC
        );

        expect($meetings[0]->name)->toBe('TD descusion 3');
        expect($meetings[1]->name)->toBe('TD descusion 2');
        expect($meetings[2]->name)->toBe('TD descusion 1');
        expect($meetings[3]->name)->toBe('Stand up 3');
    });

    it('lists meetings with partial name', function () {
        $meetings = $this->service->list(
            $this->project,
            'TD desc'
        );

        expect($meetings)->toHaveCount(3);
        expect($meetings[0]->name)->toBe('TD descusion 1');
        expect($meetings[1]->name)->toBe('TD descusion 2');
        expect($meetings[2]->name)->toBe('TD descusion 3');
    });

    it('lists meetings with matching name', function () {
        $meetings = $this->service->list(
            $this->project,
            'Stand up 3'
        );

        expect($meetings)->toHaveCount(1);
        expect($meetings[0]->name)->toBe('Stand up 3');
    });

    it('it paginates', function () {
        Config::set('app.pagination_items', 2);
        Request::merge(['page' => 5]);

        $meetings = $this->service->list(
            $this->project
        );

        expect($meetings)->toHaveCount(1);
        expect($meetings)->toBeInstanceOf(LengthAwarePaginator::class);
        expect($meetings->total())->toBe(9);
        expect($meetings->perPage())->toBe(config('app.pagination_items'));
        expect($meetings->lastPage())->toBe(5);
        expect($meetings->currentPage())->toBe(5);
        expect($meetings->hasMorePages())->toBeFalse();
        expect($meetings->previousPageUrl())->not->toBeNull();
        expect($meetings->nextPageUrl())->toBeNull();
        expect($meetings->previousPageUrl())->not->toBeNull();
    });

});
