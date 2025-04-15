<?php

use App\Actions\Access\GrantAccess;
use App\Actions\Workstream\CreateWorkstream;
use App\DTO\Access\GrantAccessDTO;
use App\DTO\Workstream\CreateWorkstreamDTO;
use App\Enums\RoleEnum;
use App\Exceptions\GrantAccessException;
use App\Models\User;
use App\Services\OrganizationService;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;

    actingAs($this->user);

});

it('cannot grant access if the user does not belongs to the same organization as the accessible', function () {
    $userWithoutOrganization = User::factory()->create();
    $workstream = (new CreateWorkstream)(new CreateWorkstreamDTO(
        organization: $this->organization,
        name: 'Test workstream',
        priority_id: $this->organization->priorities()->first()->id,
    ));

    (new GrantAccess)(
        new GrantAccessDTO(
            accessible: $workstream,
            user: $userWithoutOrganization,
            role: RoleEnum::CONTRIBUTOR

        ),
        app(OrganizationService::class)
    );

})->throws(GrantAccessException::class, 'User does not belong to this organization.');

it('cannot grant access if the user already has access', function () {
    $useWithAccess = User::factory()->create();
    $workstream = (new CreateWorkstream)(new CreateWorkstreamDTO(
        organization: $this->organization,
        name: 'Test workstream',
        priority_id: $this->organization->priorities()->first()->id,
    ));

    app(OrganizationService::class)->attachUser(
        organization: $this->organization,
        user: $useWithAccess,
        roleId: RoleEnum::CONTRIBUTOR->value
    );

    $workstream->accesses()->create([
        'user_id' => $useWithAccess->id,
        'role_id' => RoleEnum::CONTRIBUTOR->value,
    ]);

    (new GrantAccess)(
        new GrantAccessDTO(
            accessible: $workstream,
            user: $useWithAccess,
            role: RoleEnum::CONTRIBUTOR

        ), app(OrganizationService::class)
    );

})->throws(GrantAccessException::class, 'Access granted already.');

it('cannot grant access if role is not allowed', function (RoleEnum $organizationRole, RoleEnum $roleToGrant) {
    $useWithAccess = User::factory()->create();
    $workstream = (new CreateWorkstream)(new CreateWorkstreamDTO(
        organization: $this->organization,
        name: 'Test workstream',
        priority_id: $this->organization->priorities()->first()->id,
    ));

    app(OrganizationService::class)->attachUser(
        organization: $this->organization,
        user: $useWithAccess,
        roleId: $organizationRole->value,
    );

    (new GrantAccess)(
        new GrantAccessDTO(
            accessible: $workstream,
            user: $useWithAccess,
            role: $roleToGrant

        ), app(OrganizationService::class)
    );

})->with([
    [RoleEnum::VIEWER, RoleEnum::ADMIN],   // Viewer cannot be granted Admin access
    [RoleEnum::ADMIN, RoleEnum::VIEWER],   // Admin cannot be downgraded to Viewer
])->throws(GrantAccessException::class, 'Role not allowed.');

it('Grants access', function () {
    $useWithoutAccess = User::factory()->create();
    $workstream = (new CreateWorkstream)(new CreateWorkstreamDTO(
        organization: $this->organization,
        name: 'Test workstream',
        priority_id: $this->organization->priorities()->first()->id,
    ));

    app(OrganizationService::class)->attachUser(
        organization: $this->organization,
        user: $useWithoutAccess,
        roleId: RoleEnum::CONTRIBUTOR->value
    );

    (new GrantAccess)(
        new GrantAccessDTO(
            accessible: $workstream,
            user: $useWithoutAccess,
            role: RoleEnum::CONTRIBUTOR

        ), app(OrganizationService::class)
    );

    expect(DB::table('accesses')
        ->where('user_id', $useWithoutAccess->id)
        ->where('accesses.accessible_id', $workstream->id)
        ->where('accesses.accessible_type', $workstream::class)
        ->where('accesses.role_id', RoleEnum::CONTRIBUTOR->value)
        ->count()
    )->toBe(1);

});
