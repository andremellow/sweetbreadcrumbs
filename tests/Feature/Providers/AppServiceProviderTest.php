<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\User;
use App\Providers\AppServiceProvider;
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
