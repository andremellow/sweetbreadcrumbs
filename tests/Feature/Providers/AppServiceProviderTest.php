<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\User;
use App\Providers\AppServiceProvider;
use App\Services\OrganizationService;
use App\Services\UserService;

use function Pest\Laravel\actingAs;

covers(AppServiceProvider::class);
beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization'));
});

it('binds the Logged user to UserService', function () {
    actingAs($this->user);

    $userService = app(UserService::class);

    expect($userService->getUser())->toBe($this->user);
});

it('sets organization on OrganizaitonService', function () {

    // Access URL that not requires Organization
    // This case is for livewire component, using this url TO for code to pass

    actingAs($this->user)
        ->withSession(['current_organization_id' => $this->organization->id])
        ->get(route('welcome.profile'))
        ->tap(function () {
            $organizationService = app(OrganizationService::class);

            expect($organizationService->getOrganization()->id)->toBe($this->organization->id);
        })
        ->assertSessionHas('current_organization_id', $this->organization->id);

});

it('reads from sesstion if organization not present on the URL', function () {

    actingAs($this->user)
        ->get(route('dashboard', ['organization' => $this->organization->slug]));

    $organizationService = app(OrganizationService::class);

    expect($organizationService->getOrganization()->id)->toBe($this->organization->id);
});
