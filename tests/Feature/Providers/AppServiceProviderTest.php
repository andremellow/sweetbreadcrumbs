<?php

use App\Providers\AppServiceProvider;
use App\Services\UserService;

use function Pest\Laravel\actingAs;

covers(AppServiceProvider::class);
beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;
});

it('binds the Logged user to UserService', function () {
    actingAs($this->user);

    $userService = app(UserService::class);

    expect($userService->getUser())->toBe($this->user);
});
