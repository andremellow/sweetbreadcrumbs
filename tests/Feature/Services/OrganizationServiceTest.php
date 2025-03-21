<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\Organization;
use App\Models\Release;
use App\Models\Role;
use App\Models\User;
use App\Services\OrganizationService;
use App\Services\UserService;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->mockCreateOrganization = Mockery::mock(CreateOrganization::class);

    // Instantiate UserService with a user
    $this->userService = new UserService($this->user);

    $this->organizationService = new OrganizationService($this->userService, $this->mockCreateOrganization);
    $this->app->bind(UserService::class, function () {
        return new UserService($this->user);
    });
});

afterEach(function () {
    Mockery::close();
});

it('calls CreateOrganization when creating an new organization', function () {
    $mockOrganization = Mockery::mock(Organization::class);
    $createOrganizationDTO = new CreateOrganizationDTO('My new organization');

    $this->mockCreateOrganization
        ->shouldReceive('__invoke')
        ->once()
        ->with($this->user, $createOrganizationDTO)
        ->andReturn($mockOrganization);

    $organization = $this->organizationService->create(
        $this->user,
        $createOrganizationDTO
    );

    expect($organization)->toBe($mockOrganization);
});

it('returns only organization proirity dropdown data', function () {
    $this->organizationService = app(OrganizationService::class);
    $this->organizationService->create($this->user, new CreateOrganizationDTO('organization 1'));
    $organization = $this->organizationService->create($this->user, new CreateOrganizationDTO('organization 2'));
    $this->organizationService->create($this->user, new CreateOrganizationDTO('organization 3'));

    $this->organizationService->setOrganization($organization);

    $priorities = $this->organizationService->getPrioritiesDropDownData();

    expect($priorities)->toHaveCount(5);
    expect($priorities[11])->toBe('Highest');
    expect($priorities[12])->toBe('High');
    expect($priorities[13])->toBe('Midium');
    expect($priorities[14])->toBe('Low');
    expect($priorities[15])->toBe('Lowest');
});

it('returns only organization roles dropdown data', function () {
    $this->organizationService = app(OrganizationService::class);
    $this->organizationService->create($this->user, new CreateOrganizationDTO('organization 1'));
    $organization = $this->organizationService->create($this->user, new CreateOrganizationDTO('organization 2'));
    $organization2 = $this->organizationService->create($this->user, new CreateOrganizationDTO('organization 3'));
    $organization->roles()->create(['name' => 'New role', 'is_default' => false]);
    $organization2->roles()->create(['name' => 'New role for other organization', 'is_default' => false]);

    $this->organizationService->setOrganization($organization);

    $roles = $this->organizationService->getRolesDropDownData();

    expect($roles)->toHaveCount(4);
    expect($roles[7])->toBe('Admin');
    expect($roles[8])->toBe('Contributor');
    expect($roles[9])->toBe('Viewer');
    expect($roles[13])->toBe('New role');
});

it('returns default role id', function () {
    $this->organizationService = app(OrganizationService::class);
    $this->organizationService->create($this->user, new CreateOrganizationDTO('organization 1'));
    $organization = $this->organizationService->create($this->user, new CreateOrganizationDTO('organization 2'));

    $this->organizationService->setOrganization($organization);

    // Set default role to 8
    Role::where('organization_id', $organization->id)->update(['is_default' => false]);
    Role::find(8)->update(['is_default' => true]);

    $roleId = $this->organizationService->getDefaultRoleId();
    expect($roleId)->toBe(8);

    // Set default role to 9
    Role::where('organization_id', $organization->id)->update(['is_default' => false]);
    Role::find(9)->update(['is_default' => true]);

    $roleId = $this->organizationService->getDefaultRoleId();
    expect($roleId)->toBe(9);

    // Set default role to none
    Role::where('organization_id', $organization->id)->update(['is_default' => false]);

    // Should return the first one: 7
    $roleId = $this->organizationService->getDefaultRoleId();
    expect($roleId)->toBe(7);

});

it('returns only organization release dropdown data', function () {
    $this->organizationService = app(OrganizationService::class);
    $organization1 = $this->organizationService->create($this->user, new CreateOrganizationDTO('organization 1'));
    $organization2 = $this->organizationService->create($this->user, new CreateOrganizationDTO('organization 2'));

    Release::factory(5)->for($organization1)->create();
    Release::factory()->for($organization2)->create(['name' => '5.30']);
    Release::factory()->for($organization2)->create(['name' => '5.31']);
    Release::factory()->for($organization2)->create(['name' => '5.32']);

    $this->organizationService->setOrganization($organization2);

    $releasesData = $this->organizationService->getReleasesDropDownData();

    expect($releasesData)->toHaveCount(3);
    expect($releasesData[6])->toBe('5.30');
    expect($releasesData[7])->toBe('5.31');
    expect($releasesData[8])->toBe('5.32');

});
