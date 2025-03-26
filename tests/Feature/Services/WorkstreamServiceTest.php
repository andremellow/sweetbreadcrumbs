<?php

use App\Actions\Workstream\CreateWorkstream;
use App\Actions\Workstream\DeleteWorkstream;
use App\Actions\Workstream\UpdateWorkstream;
use App\DTO\Workstream\CreateWorkstreamDTO;
use App\DTO\Workstream\DeleteWorkstreamDTO;
use App\DTO\Workstream\UpdateWorkstreamDTO;
use App\Enums\SortDirection;
use App\Models\Workstream;
use App\Services\WorkstreamService;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

covers(WorkstreamService::class);

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;

    // Mock dependencies
    /** @var CreateWorkstream */
    $this->mockCreateWorkstream = Mockery::mock(CreateWorkstream::class);

    /** @var UpdateWorkstream */
    $this->mockUpdateWorkstream = Mockery::mock(UpdateWorkstream::class);

    /** @var DeleteWorkstream */
    $this->mockDeleteWorkstream = Mockery::mock(DeleteWorkstream::class);

    // Instantiate the service with mocks
    $this->service = new WorkstreamService($this->mockCreateWorkstream, $this->mockUpdateWorkstream, $this->mockDeleteWorkstream);
});

afterEach(function () {
    Mockery::close();
});

it('creates a workstream using CreateWorkstream action', function () {
    $dto = CreateWorkstreamDTO::from([
        'organization' => $this->organization,
        ...[
            'name' => 'New Workstream',
            'priority_id' => 1,
        ],
    ]);

    $mockWorkstream = Mockery::mock(Workstream::class);

    // Expect CreateWorkstream action to be called
    $this->mockCreateWorkstream
        ->shouldReceive('__invoke')
        ->once()
        ->with(
            $dto
        )
        ->andReturn($mockWorkstream);

    // Call the method
    $workstream = $this->service->create(
        $this->user,
        $dto
    );

    expect($workstream)->toBe($mockWorkstream);
});

it('updates a workstream using UpdateWorkstream action', function () {
    // Prepare test data

    $dto = UpdateWorkstreamDTO::from([
        'organization' => $this->organization,
        'workstream_id' => 1,
        'priority_id' => 1,
        'name' => 'Updated Workstream',
    ]);

    $mockWorkstream = Mockery::mock(Workstream::class);

    // Expect UpdateWorkstream action to be called
    $this->mockUpdateWorkstream
        ->shouldReceive('__invoke')
        ->once()
        ->with(
            $dto
        )
        ->andReturn($mockWorkstream);

    // Call the method
    $workstream = $this->service->update(
        $this->user,
        $dto
    );

    expect($workstream)->toBe($mockWorkstream);
});

it('deletes a workstream using DeleteWorkstream action', function () {
    /** @var Workstream */
    $mockWorkstream = Mockery::mock(Workstream::class);

    $dto = new DeleteWorkstreamDTO($mockWorkstream);

    $mockWorkstream = Mockery::mock(Workstream::class);

    // Expect DeleteWorkstream action to be called
    $this->mockDeleteWorkstream
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

describe('list workstreams', function () {

    beforeEach(function () {
        // 6	 = Highest
        // 7	 = High
        // 8	 = Midium
        // 9	 = Low
        // 10 = Lowest

        Workstream::factory()->for($this->organization)->create(['name' => 'Workstream A', 'priority_id' => 8, 'created_at' => Carbon::now()->addDay(11)]);
        Workstream::factory()->for($this->organization)->create(['name' => 'Workstream A.1', 'priority_id' => 8, 'created_at' => Carbon::now()->addDay(10)]);
        Workstream::factory()->for($this->organization)->create(['name' => 'Workstream B', 'priority_id' => 8, 'created_at' => Carbon::now()->addDay(9)]);
        Workstream::factory()->for($this->organization)->create(['name' => 'Workstream B.2', 'priority_id' => 7, 'created_at' => Carbon::now()->addDay(8)]);
        Workstream::factory()->for($this->organization)->create(['name' => 'Workstream C', 'priority_id' => 7, 'created_at' => Carbon::now()->addDay(7)]);
        Workstream::factory()->for($this->organization)->create(['name' => 'Workstream C.3', 'priority_id' => 7, 'created_at' => Carbon::now()->addDay(6)]);
        Workstream::factory()->for($this->organization)->create(['name' => 'Workstream D', 'priority_id' => 6, 'created_at' => Carbon::now()->addDay(5)]);
        Workstream::factory()->for($this->organization)->create(['name' => 'Workstream E', 'priority_id' => 6, 'created_at' => Carbon::now()->addDay(4)]);
        Workstream::factory()->for($this->organization)->create(['name' => 'Workstream G', 'priority_id' => 8, 'created_at' => Carbon::now()->addDay(3)]);
        Workstream::factory()->for($this->organization)->create(['name' => 'Workstream H', 'priority_id' => 7, 'created_at' => Carbon::now()->addDay(2)]);
        Workstream::factory()->for($this->organization)->create(['name' => 'Workstream H.1', 'priority_id' => 8, 'created_at' => Carbon::now()->addDay(1)]);
    });

    it('lists workstreams with default sorting', function () {
        $workstreams = $this->service->list($this->organization, null, null);

        expect($workstreams)->toBeInstanceOf(LengthAwarePaginator::class);
        expect($workstreams->total())->toBe(11);
        expect($workstreams[0]->name)->toBe('Workstream A');
        expect($workstreams[1]->name)->toBe('Workstream A.1');
        expect($workstreams[2]->name)->toBe('Workstream B');
    });

    it('lists workstreams sorted by priority', function () {
        $workstreams = $this->service->list(
            $this->organization,
            null,
            null,
            'priority',
            SortDirection::ASC
        );

        expect($workstreams->total())->toBe(11);
        expect($workstreams)->toBeInstanceOf(LengthAwarePaginator::class);
        expect($workstreams[0]->priority_id)->toBe(6);
        expect($workstreams[1]->priority_id)->toBe(6);
        expect($workstreams[2]->priority_id)->toBe(7);
    });

    it('lists workstreams sorted by date', function () {
        $workstreams = $this->service->list(
            $this->organization,
            null,
            null,
            'date',
            SortDirection::ASC
        );

        expect($workstreams->total())->toBe(11);
        expect($workstreams)->toBeInstanceOf(LengthAwarePaginator::class);
        expect($workstreams[0]->name)->toBe('Workstream H.1');
        expect($workstreams[1]->name)->toBe('Workstream H');
        expect($workstreams[2]->name)->toBe('Workstream G');
    });

    it('filters workstreams by contain name', function () {
        $workstreams = $this->service->list($this->organization, 'Workstream B', null);

        expect($workstreams)->toHaveCount(2);
        expect($workstreams[0]->name)->toBe('Workstream B');
        expect($workstreams[1]->name)->toBe('Workstream B.2');
    });

    it('filters workstreams by maching name', function () {
        $workstreams = $this->service->list($this->organization, 'Workstream B.2', null);

        expect($workstreams)->toHaveCount(1);
        expect($workstreams[0]->name)->toBe('Workstream B.2');
    });

    it('filters workstreams by priority', function () {
        $workstreams = $this->service->list($this->organization, null, 7);

        expect($workstreams)->toBeInstanceOf(LengthAwarePaginator::class);
        expect($workstreams)->toHaveCount(4);
        expect($workstreams[0]->priority_id)->toBe(7);
        expect($workstreams[1]->priority_id)->toBe(7);
        expect($workstreams[2]->priority_id)->toBe(7);
        expect($workstreams[3]->priority_id)->toBe(7);
    });

    it('paginates workstreams correctly', function () {
        Config::set('app.pagination_items', 2);
        Request::merge(['page' => 6]);

        $workstreams = $this->service->list($this->organization, null, null);

        expect($workstreams)->toBeInstanceOf(LengthAwarePaginator::class);
        expect($workstreams->total())->toBe(11);
        expect($workstreams->perPage())->toBe(2);
        expect($workstreams->lastPage())->toBe(6);
        expect($workstreams->currentPage())->toBe(6);
        expect($workstreams->hasMorePages())->toBeFalse();
    });
});
