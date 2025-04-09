<?php

use App\DTO\Access\GrantAccessDTO;

covers(GrantAccessDTO::class);

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;

});

it('Validate required fields', function () {
    GrantAccessDTO::from([
        'AccessibleContract' => '',
        'user' => '',
        'role' => '',
    ]);
})->throws('The accessible field is required. (and 1 more error)');
