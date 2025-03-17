<?php

use App\Actions\Meeting\CreateMeeting;
use App\Actions\Meeting\DeleteMeeting;
use App\Actions\Meeting\UpdateMeeting;
use App\Actions\Organization\CreateOrganization;
use App\DTO\Meeting\CreateMeetingDTO;
use App\DTO\Meeting\DeleteMeetingDTO;
use App\DTO\Meeting\UpdateMeetingDTO;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Enums\SortDirection;
use App\Models\Meeting;
use App\Models\User;
use App\Models\Workstream;
use App\Services\MeetingService;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

covers(MeetingService::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();
    /** @var CreateMeeting */
    $this->mockCreateMeeting = Mockery::mock(CreateMeeting::class);
    /** @var UpdateMeeting */
    $this->mockUpdateMeeting = Mockery::mock(UpdateMeeting::class);
    /** @var DeleteMeeting */
    $this->mockDeleteMeeting = Mockery::mock(DeleteMeeting::class);
    $this->service = new MeetingService($this->mockCreateMeeting, $this->mockUpdateMeeting, $this->mockDeleteMeeting);
});

afterEach(function () {
    Mockery::close();
});

it('creates a meeting using CreateMeeting action', function () {
    /** @var Workstream $workstream */
    $workstream = Mockery::mock(Workstream::class);
    $name = 'Team Sync';
    $description = 'Weekly team meeting';
    $date = '2024-03-01 10:00:00';
    $carbonDate = Carbon::parse($date);

    $mockMeeting = Mockery::mock(Meeting::class);

    // Expect the CreateMeeting action to be called with these parameters
    $this->mockCreateMeeting
        ->shouldReceive('__invoke')
        ->once()
        ->with(CreateMeetingDTO::class)
        ->andReturn($mockMeeting);

    // Call the method
    $meeting = $this->service->create(
        $this->user,
        new CreateMeetingDTO(
            $workstream, $name, $description, $carbonDate
        )
    );

    expect($meeting)->toBe($mockMeeting);
});

it('updates a meeting using UpdateMeeting action', function () {
    /** @var Workstream */
    $workstream = Mockery::mock(Workstream::class);

    $dto = new UpdateMeetingDTO(
        $workstream,
        1,
        'Updated Meeting',
        'Updated description',
        Carbon::now()
    );

    $mockMeeting = Mockery::mock(Meeting::class);

    // Expect the UpdateMeeting action to be called with these parameters
    $this->mockUpdateMeeting
        ->shouldReceive('__invoke')
        ->once()
        ->with($dto)

        ->andReturn($mockMeeting);

    // Call the method
    $meeting = $this->service->update($this->user, $dto);

    expect($meeting)->toBe($mockMeeting);
});

it('deletes a meeting using UpdateMeeting action', function () {
    $workstream = Mockery::mock(Workstream::class);
    /** @var Meeting */
    $mockMeeting = Mockery::mock(Meeting::class);
    $dto = new DeleteMeetingDTO($mockMeeting);
    // Expect the UpdateMeeting action to be called with these parameters
    $this->mockDeleteMeeting
        ->shouldReceive('__invoke')
        ->once()
        ->with($dto);

    // Call the method
    $this->service->delete(
        $this->user,
        $dto
    );

});

describe('list meetings', function () {
    beforeEach(function () {
        Meeting::factory()->for($this->workstream)->create(['name' => 'Stand up 1', 'description' => 'Description for Stand up 1', 'date' => Carbon::now()->addDays(9)]);
        Meeting::factory()->for($this->workstream)->create(['name' => 'Stand up 2', 'description' => 'Description for Stand up 2', 'date' => Carbon::now()->addDays(8)]);
        Meeting::factory()->for($this->workstream)->create(['name' => 'Stand up 3', 'description' => 'Description for Stand up 3', 'date' => Carbon::now()->addDays(7)]);
        Meeting::factory()->for($this->workstream)->create(['name' => 'TD descusion 1', 'description' => 'Description for TD descusion 1', 'date' => Carbon::now()->addDays(6)]);
        Meeting::factory()->for($this->workstream)->create(['name' => 'TD descusion 2', 'description' => 'Description for TD descusion 2', 'date' => Carbon::now()->addDays(5)]);
        Meeting::factory()->for($this->workstream)->create(['name' => 'TD descusion 3', 'description' => 'Description for TD descusion 3', 'date' => Carbon::now()->addDays(4)]);
        Meeting::factory()->for($this->workstream)->create(['name' => 'Sprint planing 1', 'description' => 'Description for Sprint planing 1', 'date' => Carbon::now()->addDays(3)]);
        Meeting::factory()->for($this->workstream)->create(['name' => 'Sprint planing 2', 'description' => 'Description for Sprint planing 2', 'date' => Carbon::now()->addDays(2)]);
        Meeting::factory()->for($this->workstream)->create(['name' => 'Sprint planing 3', 'description' => 'Description for Sprint planing 3', 'date' => Carbon::now()->addDays(1)]);
    });

    it('lists meetings default sort by name if invalid argument is given', function () {
        $meetings = $this->service->list(
            $this->workstream,
            null,
            null,
            null,
            'any_invalid_sort_fields',
            SortDirection::ASC
        );

        expect($meetings)->toHaveCount(9);
        expect($meetings[0]->name)->toBe('Sprint planing 1');
    });

    it('lists meetings with default sorting', function () {
        $meetings = $this->service->list(
            $this->workstream
        );

        expect($meetings)->toHaveCount(9);
        expect($meetings[0]->name)->toBe('Sprint planing 1');
        expect($meetings[1]->name)->toBe('Sprint planing 2');
        expect($meetings[2]->name)->toBe('Sprint planing 3');
        expect($meetings[3]->name)->toBe('Stand up 1');
    });

    it('lists meetings with default desc sorting', function () {
        $meetings = $this->service->list(
            $this->workstream,
            null,
            null,
            null,
            'name',
            SortDirection::DESC
        );

        expect($meetings[0]->name)->toBe('TD descusion 3');
        expect($meetings[1]->name)->toBe('TD descusion 2');
        expect($meetings[2]->name)->toBe('TD descusion 1');
        expect($meetings[3]->name)->toBe('Stand up 3');
    });

    it('lists meetings with date range sorting', function () {
        $meetings = $this->service->list(
            $this->workstream,
            null,
            Carbon::now()->today(),
            Carbon::now()->addDays(7),
            'date',
            SortDirection::DESC
        );

        expect($meetings)->toHaveCount(7);
        expect($meetings[0]->name)->toBe('Stand up 3');
        expect($meetings[1]->name)->toBe('TD descusion 1');
        expect($meetings[2]->name)->toBe('TD descusion 2');
        expect($meetings[3]->name)->toBe('TD descusion 3');
    });

    it('lists meetings with partial name', function () {
        $meetings = $this->service->list(
            $this->workstream,
            'TD desc'
        );

        expect($meetings)->toHaveCount(3);
        expect($meetings[0]->name)->toBe('TD descusion 1');
        expect($meetings[1]->name)->toBe('TD descusion 2');
        expect($meetings[2]->name)->toBe('TD descusion 3');
    });

    it('lists meetings with matching name', function () {
        $meetings = $this->service->list(
            $this->workstream,
            'Stand up 3'
        );

        expect($meetings)->toHaveCount(1);
        expect($meetings[0]->name)->toBe('Stand up 3');
    });

    it('paginates', function () {
        Config::set('app.pagination_items', 2);
        Request::merge(['page' => 5]);

        $meetings = $this->service->list(
            $this->workstream
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
