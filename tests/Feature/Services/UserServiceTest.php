<?php

use App\Models\Organization;
use App\Models\User;
use App\Services\UserService;

use function Pest\Laravel\{actingAs};

it('has organization associated', function () {
    $user = User::factory()->create();
    $userService = new UserService($user);

    expect($userService->hasOrganizations())->toBeFalse();

    Organization::factory()->hasAttached($user)->create();

    expect($userService->hasOrganizations())->toBeTrue();

});
