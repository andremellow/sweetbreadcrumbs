<?php

use App\DTO\Access\GrantAccessDTO;
use App\Enums\EventEnum;
use App\Enums\RoleEnum;
use App\Enums\SortDirection;
use App\Livewire\Meeting\ListMeetings;
use App\Livewire\Meeting\MeetingModal;
use App\Models\Meeting;
use App\Models\User;
use App\Models\Workstream;
use App\Services\AccessService;
use App\Services\MeetingService;
use App\Services\OrganizationService;
use Flux\DateRange;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    // Create test user and organization
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;
    $this->app['session']->start();
    session(['current_organization_id' => $this->organization->id]);

    // Create test workstreams
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();
    $this->meetings = Meeting::factory(3)->for($this->workstream)->create();

    $this->organizationService = app(OrganizationService::class);

    URL::defaults(['organization' => $this->organization->slug]);
    View::share('currentOrganizationSlug', $this->organization->slug);
});

describe('As Viewer', function () {
    beforeEach(function () {
        $this->newUser = User::factory()->create();
        ActingAs($this->user);

        $accessService = app(AccessService::class);
        $this->organizationService->attachUser(
            organization: $this->organization,
            user: $this->newUser,
            roleId: RoleEnum::VIEWER->value,
        );

        $accessService->grantAccess(new GrantAccessDTO(
            accessible: $this->workstream,
            user: $this->newUser,
            role: RoleEnum::VIEWER,
        ), $this->organizationService);
    });

    it('does not show create button VIEW Role', function () {

        Livewire::actingAs($this->newUser)
            ->test(ListMeetings::class, ['workstream' => $this->workstream])
            ->assertDontSeeText("Create meeting");
    });


    it('validates the permission for the save function', function () {

        Livewire::actingAs($this->newUser)
            ->test(MeetingModal::class, ['workstream' => $this->workstream])
            ->call('save')
            ->assertStatus(403);
    });

});

describe('As Contributor', function () {
    beforeEach(function () {
        $this->newUser = User::factory()->create();
        ActingAs($this->user);

        $accessService = app(AccessService::class);
        $this->organizationService->attachUser(
            organization: $this->organization,
            user: $this->newUser,
            roleId: RoleEnum::CONTRIBUTOR->value,
        );

        $accessService->grantAccess(new GrantAccessDTO(
            accessible: $this->workstream,
            user: $this->newUser,
            role: RoleEnum::CONTRIBUTOR,
        ), $this->organizationService);
    });

    it('shows create button', function () {

        Livewire::actingAs($this->newUser)
            ->test(ListMeetings::class, ['workstream' => $this->workstream])
            ->assertSeeText("Create meeting");
    });


    it('validates the permission for the add function', function () {

        Livewire::actingAs($this->newUser)
            ->test(MeetingModal::class, ['workstream' => $this->workstream])
            ->call('save')
            ->assertStatus(200);
    });

});


describe('As Admin', function () {
    beforeEach(function () {
        $this->newUser = User::factory()->create();
        ActingAs($this->user);

        $accessService = app(AccessService::class);
        $this->organizationService->attachUser(
            organization: $this->organization,
            user: $this->newUser,
            roleId: RoleEnum::ADMIN->value,
        );

        $accessService->grantAccess(new GrantAccessDTO(
            accessible: $this->workstream,
            user: $this->newUser,
            role: RoleEnum::ADMIN,
        ), $this->organizationService);
    });

    it('shows create button', function () {

        Livewire::actingAs($this->newUser)
            ->test(ListMeetings::class, ['workstream' => $this->workstream])
            ->assertSeeText("Create meeting");
    });


    it('validates the permission for the add function', function () {

        Livewire::actingAs($this->newUser)
            ->test(MeetingModal::class, ['workstream' => $this->workstream])
            ->call('save')
            ->assertStatus(200);
    });

});
