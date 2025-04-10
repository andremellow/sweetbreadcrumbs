<?php

use App\DTO\Access\GrantAccessDTO;
use App\Enums\RoleEnum;
use App\Exceptions\GrantAccessException;
use App\Livewire\Access\MemberAccessModal;
use App\Models\User;
use App\Models\Workstream;
use App\Services\AccessService;
use App\Services\OrganizationService;
use App\Services\UserService;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;
    Context::add('current_organization', $this->organization);

    $this->app->bind(UserService::class, function () {
        return new UserService($this->user);
    });

    // Create test workstreams
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();

    URL::defaults(['organization' => $this->organization->slug]);

    $this->usersWithAccess = User::Factory(10)->create();
    $this->usersWithoutAccess = User::Factory(10)->create();

    $this->organizationService = app(OrganizationService::class);
    $this->organizationService->setOrganization($this->organization);
    $this->accessService = app(AccessService::class);

    foreach ([...$this->usersWithAccess, ...$this->usersWithoutAccess] as $user) {

        // Attach to the Organization
        $this->organizationService->attachUser(
            organization: $this->organization,
            user: $user,
            roleId: RoleEnum::CONTRIBUTOR->value
        );
    }

    foreach ($this->usersWithAccess as $user) {
        // Attach to Workstream
        $this->accessService->grantAccess(
            grantAccessDTO: new GrantAccessDTO(
                accessible: $this->workstream,
                user: $user,
                role: RoleEnum::CONTRIBUTOR
            ),
            organizationService: $this->organizationService
        );
    }

});

afterEach(function () {
    Mockery::close();
});

it('renders the WorkstreamModal component successfully', function () {
    Livewire::actingAs($this->user)
        ->test(MemberAccessModal::class, [
            'accessible' => $this->workstream,
        ])
        ->assertStatus(200)
        ->assertSee('Email')
        ->assertSee('Role')
        ->assertSee('Add')
        ->assertSeeHtml('wire:model="email"')
        ->assertSeeHtml('wire:model.live="roleId"')
        ->assertSeeHtml('data-modal="member-access-modal"')
        ->assertSeeHtml('Add');
});

it('Adds a user', function () {

    $organizationService = app(OrganizationService::class);

    $user = User::Factory()->create();

    $organizationService->attachUser(
        organization: $this->organization,
        user: $user,
        roleId: RoleEnum::VIEWER->value
    );

    Livewire::actingAs($this->user)
        ->test(MemberAccessModal::class, [
            'accessible' => $this->workstream,
        ])
        ->set('email', $user->email)
        ->set('roleId', RoleEnum::VIEWER->value)
        ->call('add')
        ->assertSee($user->first_name)
        ->assertSee($user->last_name);

    $this->assertDatabaseHas('accesses', [
        'accessible_type' => Workstream::class,
        'accessible_id' => $this->workstream->id,
        'user_id' => $user->id,
        'role_id' => RoleEnum::VIEWER->value,
    ]);

});

it('Validates role is allowed', function () {

    $organizationService = app(OrganizationService::class);

    $user = User::Factory()->create();

    $organizationService->attachUser(
        organization: $this->organization,
        user: $user,
        roleId: RoleEnum::VIEWER->value
    );

    Livewire::actingAs($this->user)
        ->test(MemberAccessModal::class, [
            'accessible' => $this->workstream,
        ])
        ->set('email', $user->email)
        ->set('roleId', RoleEnum::CONTRIBUTOR->value)
        ->call('add');
})->throws(GrantAccessException::class, 'Role not allowed');

it('loads existing members', function () {
    Livewire::actingAs($this->user)
        ->test(MemberAccessModal::class, [
            'accessible' => $this->workstream,
        ])
        ->assertSee($this->usersWithAccess[0]->first_name)
        ->assertSee($this->usersWithAccess[0]->last_name)
        ->assertSee($this->usersWithAccess[0]->email)
        ->assertSee($this->usersWithAccess[1]->first_name)
        ->assertSee($this->usersWithAccess[1]->last_name)
        ->assertSee($this->usersWithAccess[1]->email)
        ->assertSee($this->usersWithAccess[2]->first_name)
        ->assertSee($this->usersWithAccess[2]->last_name)
        ->assertSee($this->usersWithAccess[2]->email)
        ->assertSee($this->usersWithAccess[3]->first_name)
        ->assertSee($this->usersWithAccess[3]->last_name)
        ->assertSee($this->usersWithAccess[3]->email)
        ->assertSee($this->usersWithAccess[4]->first_name)
        ->assertSee($this->usersWithAccess[4]->last_name)
        ->assertSee($this->usersWithAccess[4]->email)
        ->assertDontSee($this->usersWithoutAccess[4]->first_name)
        ->assertDontSee($this->usersWithoutAccess[4]->last_name)
        ->assertDontSee($this->usersWithoutAccess[4]->email);
});

it('revokes access', function () {

    Livewire::actingAs($this->user)
        ->test(MemberAccessModal::class, [
            'accessible' => $this->workstream,
        ])
        ->call('revoke', $this->usersWithAccess[0]->id);

    $this->assertDatabaseMissing('accesses', [
        'accessible_type' => Workstream::class,
        'accessible_id' => $this->workstream->id,
        'user_id' => $this->usersWithAccess[0]->id,
    ]);

});
