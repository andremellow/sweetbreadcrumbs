<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\User;
use App\Services\OrganizationService;
use Illuminate\Support\Facades\Session;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\post;

beforeEach(function () {
    // Create User and Organization
    $this->user = User::factory()->create();

    // Authenticate User
    actingAs($this->user);
});

afterEach(function () {
    Mockery::close();
});

it('displays a welcome form', function () {
    $response = getJson(route('welcome.organization'));

    // GO here is to test if the json structure match what it needs to be.
    $response->assertInertia(fn (Assert $page) => $page
        ->component('welcome/organization')
    );

    $response->assertStatus(200);
});

describe('Organization modifications', function () {
    beforeEach(function () {
        $this->mockOrganizationService = Mockery::mock(OrganizationService::class);
        $this->app->instance(OrganizationService::class, $this->mockOrganizationService);
    });

    it('creates a new organization and redirects', function () {
        $organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization'));
        $projectData = [
            'name' => 'New Organization',
        ];

        // ✅ Expect DTO conversion & service method call
        $this->mockOrganizationService
            ->shouldReceive('create')
            ->once()
            ->with(
                User::class,
                CreateOrganizationDTO::class
            )
            ->andReturn($organization);

        $this->mockOrganizationService
            ->shouldReceive('setOrganization')
            ->once();

        // Send request
        $response = post(route('welcome.organization.store', [
            'organization' => $organization->slug,
        ]), $projectData);

        $response->assertRedirect(route('dashboard', [
            'organization' => $organization->slug,
        ]));

        // ✅ Ensure success message is in session
        expect(Session::get('success'))->toBe('Organization created');
    });
});
