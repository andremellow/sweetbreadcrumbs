<?php

use App\Actions\Access\RevokeAccess;
use App\Actions\Workstream\CreateWorkstream;
use App\DTO\Access\RevokeAccessDTO;
use App\DTO\Workstream\CreateWorkstreamDTO;
use App\Enums\RoleEnum;
use App\Models\User;
use App\Services\OrganizationService;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;

    actingAs($this->user);

});

it('Revoke grant access', function () {
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

    expect(DB::table('accesses')
        ->where('user_id', $useWithAccess->id)
        ->where('accesses.accessible_id', $workstream->id)
        ->where('accesses.accessible_type', $workstream::class)
        ->where('accesses.role_id', RoleEnum::CONTRIBUTOR->value)
        ->count()
    )->toBe(1);

    (new RevokeAccess)(
        new RevokeAccessDTO(
            accessible: $workstream,
            user_id: $useWithAccess->id,

        ), app(OrganizationService::class)
    );

    expect(DB::table('accesses')
        ->where('user_id', $useWithAccess->id)
        ->where('accesses.accessible_id', $workstream->id)
        ->where('accesses.accessible_type', $workstream::class)
        ->where('accesses.role_id', RoleEnum::CONTRIBUTOR->value)
        ->count()
    )->toBe(0);

});

it('Revoke grant access even if access does not exists', function () {
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

    expect(DB::table('accesses')
        ->where('user_id', $useWithAccess->id)
        ->where('accesses.accessible_id', $workstream->id)
        ->where('accesses.accessible_type', $workstream::class)
        ->where('accesses.role_id', RoleEnum::CONTRIBUTOR->value)
        ->count()
    )->toBe(0);

    (new RevokeAccess)(
        new RevokeAccessDTO(
            accessible: $workstream,
            user_id: $useWithAccess->id,

        ), app(OrganizationService::class)
    );

    expect(DB::table('accesses')
        ->where('user_id', $useWithAccess->id)
        ->where('accesses.accessible_id', $workstream->id)
        ->where('accesses.accessible_type', $workstream::class)
        ->where('accesses.role_id', RoleEnum::CONTRIBUTOR->value)
        ->count()
    )->toBe(0);

});
