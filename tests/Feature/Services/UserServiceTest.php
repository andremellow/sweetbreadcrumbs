<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\Organization;
use App\Models\Workstream;
use App\Models\User;
use App\Services\UserService;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization'));

    // Instantiate UserService with a user
    $this->userService = new UserService($this->user);
});

afterEach(function () {
    Mockery::close();
});

it('sets a user', function () {
    $newUser = User::factory()->create();

    expect($this->userService->setUser($newUser))->toBeInstanceOf(UserService::class);
    expect($this->userService->setUser($newUser))->not->toBe($this->user);
    expect($this->userService->getUser())->toBe($newUser);
});

it('sets an organization', function () {
    $newOrganization = Organization::factory()->create();
    $this->userService->setOrganization($newOrganization);

    expect($this->userService->getCurrentOrganization())->toBe($newOrganization);
});

it('checks if user has organizations', function () {
    expect($this->userService->hasOrganizations())->toBeTrue();

    // Create a new user without an organization
    $newUser = User::factory()->create();
    $newUserService = new UserService($newUser);

    expect($newUserService->hasOrganizations())->toBeFalse();
});

it('gets the current organization', function () {
    // Should return the first attached organization by default
    expect($this->userService->getCurrentOrganization()->id)->toBe($this->organization->id);

    // Set a different organization
    $newOrganization = Organization::factory()->hasAttached($this->user)->create();
    $this->userService->setOrganization($newOrganization);

    // Now it should return the newly set organization
    expect($this->userService->getCurrentOrganization())->toBe($newOrganization);
});

it('gets all user organizations', function () {
    // Create another organization and attach it
    $newOrganization = Organization::factory()->create();
    $this->user->organizations()->attach($newOrganization);

    $organizations = $this->userService->getOrganizations();

    expect($organizations)->toHaveCount(2);
    expect($organizations[0])->toBeInstanceOf(Organization::class);
    expect($organizations[1])->toBeInstanceOf(Organization::class);
});

it('gets all workstreams from the current organization', function () {
    $factoryWorkstreams = Workstream::factory(4)->for($this->organization)->withPriority($this->organization)->create()->sortBy('name')->values();

    $workstreams = $this->userService->getWorkstreams();

    expect($workstreams)->toBeCollection();
    expect($workstreams)->toHaveCount(4);
    expect($workstreams[0]->name)->toBe($factoryWorkstreams[0]->name);
    expect($workstreams[1]->name)->toBe($factoryWorkstreams[1]->name);
    expect($workstreams[2]->name)->toBe($factoryWorkstreams[2]->name);
    expect($workstreams[3]->name)->toBe($factoryWorkstreams[3]->name);
});

it('retrieves organization by slug', function () {
    $organizationBySlug = UserService::getOrganizationBySlug($this->user, $this->organization->slug);

    expect($organizationBySlug)->toBeInstanceOf(Organization::class);
    expect($organizationBySlug->id)->toBe($this->organization->id);

    // Test with a non-existent slug
    $notFound = UserService::getOrganizationBySlug($this->user, 'non-existent-slug');

    expect($notFound)->toBeNull();
});
