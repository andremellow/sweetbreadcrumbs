<?php

use App\DTO\Meeting\CreateMeetingDTO;
use App\DTO\Meeting\DeleteMeetingDTO;
use App\Models\Meeting;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use App\Services\MeetingService;
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
    $this->organization = Organization::factory()->create();
    $this->project = Project::factory()->for($this->organization)->create();

    // ✅ Mock MeetingService
    $this->mockMeetingService = Mockery::mock(MeetingService::class);

    // Authenticate User
    actingAs($this->user);
});

afterEach(function () {
    Mockery::close();
});

it('displays a list of meetings', function () {
    // Create test meetings
    Meeting::factory()->count(3)->for($this->project)->create();

    $response = getJson(route('projects.meetings.index', [
        'organization' => $this->organization->slug,
        'project' => $this->project->id,
    ]));

    // GO here is to test if the json structure match what it needs to be.
    $response->assertInertia(fn (Assert $page) => $page
        ->component('projects/meetings')
        ->has('filters', fn (Assert $page) => $page
            ->etc()
        )
        ->has('sortable', fn (Assert $page) => $page
            ->where('sorted_by', 'name')
            ->where('sorted_direction', 'desc')
        )
        ->has('meetings', fn (Assert $page) => $page
            ->has('data', 3)
            ->where('per_page', 15) // Assert has a pagination element
            ->etc()
        )
        ->has('meetings.data.0', fn (Assert $page) => $page
            ->has('id')
            ->has('name')
            ->has('description')
            ->has('date')
            ->has('project_id')
            ->has('created_at')
        )
    );

    $response->assertStatus(200);
    $response->assertSee('sortable'); // ✅ Ensures sorting data is passed
    $response->assertSee('filters'); // ✅ Ensures filters are available
});

it('loads meeting to be edited', function () {
    // Create test meetings
    $meeting = Meeting::factory()->for($this->project)->create();

    $response = getJson(route('projects.meetings.edit', [
        'organization' => $this->organization->slug,
        'project' => $this->project->id,
        'meeting' => $meeting->id,
    ]));

    // GO here is to test if the json structure match what it needs to be.
    $response->assertInertia(fn (Assert $page) => $page
        ->component('projects/meetings')
        ->has('meeting', fn (Assert $page) => $page
            ->has('id')
            ->has('name')
            ->has('description')
            ->has('date')
            ->has('project_id')
            ->has('created_at')
        )
    );

    $response->assertStatus(200);
});

describe('Changes database', function () {
    beforeEach(function () {
        $this->app->instance(MeetingService::class, $this->mockMeetingService);
    });

    it('creates a new meeting and redirects', function () {
        // ✅ Prepare valid meeting data
        $meetingData = [
            'name' => 'Sprint Planning',
            'description' => 'Discuss next sprint tasks',
            'date' => now()->addWeek()->format(config('app.save_date_format')),
        ];

        // ✅ Expect DTO conversion & service method call
        $this->mockMeetingService
            ->shouldReceive('create')
            ->once()
            ->with(
                User::class,
                CreateMeetingDTO::class
            );

        // Send request
        $response = post(route('projects.meetings.store', [
            'organization' => $this->organization->slug,
            'project' => $this->project->id,
        ]), $meetingData);

        $response->assertRedirect(route('projects.meetings.index', [
            'organization' => $this->organization->slug,
            'project' => $this->project->id,
        ]));

        // ✅ Ensure success message is in session
        expect(Session::get('success'))->toBe('Meeting created');
    });

    it('returns validation errors when creating a meeting with invalid data', function () {
        // Send request with missing fields
        $response = post(route('projects.meetings.store', [
            'organization' => $this->organization->slug,
            'project' => $this->project->id,
        ]), []);

        // ✅ Expect validation failure
        $response->assertSessionHasErrors(['name', 'description', 'date']);
    });

    it('returns date validation errors when creating a meeting with invalid date format', function () {
        // Send request with missing fields
        $response = post(route('projects.meetings.store', [
            'organization' => $this->organization->slug,
            'project' => $this->project->id,
            'name' => 'Sprint Planning',
            'description' => 'Discuss next sprint tasks',
            'date' => '04/25/2025',
        ]), []);

        // ✅ Expect validation failure
        $response->assertSessionHasErrors([
            'date' => 'The date field must match the format Y/m/d.',
        ]);
    });

    it('updates a meeting and redirects', function () {
        $meeting = Meeting::factory()->for($this->project)->create();

        // ✅ Prepare update data
        $updateData = [
            'name' => 'Updated Meeting Name',
            'description' => 'Updated meeting details',
            'date' => now()->addDays(10)->format(config('app.save_date_format')),
        ];

        // ✅ Expect service update method to be called
        $this->mockMeetingService
            ->shouldReceive('update')
            ->once();

        // Send request
        $response = patch(route('projects.meetings.update', [
            'organization' => $this->organization->slug,
            'project' => $this->project->id,
            'meeting' => $meeting->id,
        ]), $updateData);

        $response->assertRedirect(route('projects.meetings.index', [
            'organization' => $this->organization->slug,
            'project' => $this->project->id,
        ]));

        expect(Session::get('success'))->toBe('Meeting updated');
    });

    it('deletes a meeting and redirects', function () {
        $meeting = Meeting::factory()->for($this->project)->create();

        // ✅ Expect service delete method to be called
        $this->mockMeetingService
            ->shouldReceive('delete')
            ->once()
            ->with(
                $this->user,
                DeleteMeetingDTO::class
            );

        $response = delete(route('projects.meetings.destroy', [
            'organization' => $this->organization->slug,
            'project' => $this->project->id,
            'meeting' => $meeting->id,
        ]));

        $response->assertRedirect(route('projects.meetings.index', [
            'organization' => $this->organization->slug,
            'project' => $this->project->id,
        ]));

        expect(Session::get('success'))->toBe('Meeting deleted');
    });
});
