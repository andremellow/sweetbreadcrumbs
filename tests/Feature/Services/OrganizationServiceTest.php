<?php

use App\Actions\Organization\CreateOrganization;
use App\Models\Organization;
use App\Models\Release;
use App\Models\User;
use App\Services\OrganizationService;

beforeEach(function () {
    $this->user = User::factory()->create();
    /** @var CreateOrganization */
    $this->mockCreateOrganization = Mockery::mock(CreateOrganization::class);

    $this->organizationService = new OrganizationService($this->mockCreateOrganization);
});

it('It sets an Organization', function () {
    $organization = Organization::factory()->hasAttached($this->user)->create();

    expect($this->organizationService->getOrganization())->toBeNull();
    $this->organizationService->setOrganization($organization);
    expect($this->organizationService->getOrganization())->toBe($organization);
});

it('It creates Organization', function () {
    $organization = Organization::factory()->hasAttached($this->user)->create();

    $this->mockCreateOrganization
        ->shouldReceive('__invoke')
        ->once()
        ->with($this->user, 'New organization name')
        ->andReturn($organization);

    $createdOrganization = $this->organizationService->create($this->user, 'New organization name');

    expect($createdOrganization->name)->toBe($organization->name);
});

describe('Organization Data', function () {
    beforeEach(function () {
        $this->organizationWithData = (new CreateOrganization)(
            $this->user,
            'OrganizationWithData'
        );

        $this->organizationService->setOrganization($this->organizationWithData);

    });

    it('Gets priorities Drop Down Data', function () {
        $priorities = $this->organizationService->getPrioritiesDropDownData()->toArray();
        $priorities = array_values($priorities);

        expect($priorities)->toHaveCount(4);
        expect($priorities[0])->toBe('Low');
        expect($priorities[1])->toBe('Mid');
        expect($priorities[2])->toBe('High');
        expect($priorities[3])->toBe('Urgent');
    });

    it('Gets releases Drop Down Data', function () {
        $releases = Release::factory(3)->for($this->organizationWithData)->create();

        $releasesDropDownData = $this->organizationService->getReleasesDropDownData()->toArray();
        $releasesDropDownData = array_values($releasesDropDownData);

        expect($releasesDropDownData)->toHaveCount(3);
        expect($releasesDropDownData[0])->toBe($releases[0]->name);
        expect($releasesDropDownData[1])->toBe($releases[1]->name);
        expect($releasesDropDownData[2])->toBe($releases[2]->name);

    });

});
