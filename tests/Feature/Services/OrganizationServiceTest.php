<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\Organization;
use App\Models\Release;
use App\Models\User;
use App\Services\OrganizationService;
use App\Services\UserService;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->mockCreateOrganization = Mockery::mock(CreateOrganization::class);

    $this->organizationService = new OrganizationService($this->mockCreateOrganization);

    // Instantiate UserService with a user
    $this->userService = new UserService($this->user);
});

afterEach(function () {
    Mockery::close();
});

it('it sets and get an organization', function () {
    expect($this->organizationService->getOrganization())->toBeNull();

    $organization = Organization::factory()->create();
    $this->organizationService->setOrganization($organization);

    expect($this->organizationService->getOrganization())->toBe($organization);

});

it('it calls CreateOrganization when creating an new organization', function () {
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

it('it returns only organization proirity dropdown data', function () {
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

it('it returns only organization release dropdown data', function () {
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
