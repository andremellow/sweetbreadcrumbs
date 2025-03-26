<?php

use App\Providers\AppServiceProvider;
use App\Rules\NotOnTeam;

covers(AppServiceProvider::class);
beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;
});

it('binds the Logged user to UserService', function () {
    $notOnTeam = new NotOnTeam($this->organization->id);

    $notOnTeam->validate(
        attribute: 'email',
        value: $this->user->email,
        fail: fn ($message) => expect($message)->toBe("{$this->user->email} is already part of yor team")
    );

});
