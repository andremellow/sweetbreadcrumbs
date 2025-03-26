<?php

use App\Models\User;
use App\Providers\AppServiceProvider;
use App\Services\UserService;
use Laravel\Pennant\Feature;

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

it('feature dev is active to the right emails', function () {
    $user = User::factory()->create(['email' => 'andremellow@gmail.com']);
    actingAs($user);

    expect(Feature::active('dev'))->toBe(true);
});
