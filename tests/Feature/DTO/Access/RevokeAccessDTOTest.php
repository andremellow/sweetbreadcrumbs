<?php

use App\DTO\Access\RevokeAccessDTO;

covers(RevokeAccessDTO::class);

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;

});

it('Validate required fields', function () {
    RevokeAccessDTO::from([
        'AccessibleContract' => '',
        'user' => '',
        'role' => '',
    ]);
})->throws('The accessible field is required. (and 1 more error)');
