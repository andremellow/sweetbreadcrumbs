<?php

use App\DTO\Invite\CreateInviteDTO;
use App\Models\Invite;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Validation\ValidationException;

covers(CreateInviteDTO::class);

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;
    $this->invite = Invite::factory()->for($this->organization)->for($this->user, 'inviter')->create(['role_id' => 5]);
});

it('cannot validate if organizaiton is not preset', function () {
    CreateInviteDTO::from([
        'user' => '',
        'organization' => '',
        'email' => '',
        'role_id' => '',
    ]);
})->throws('Attempt to read property "id" on string');

it('validates required fields', function () {
    CreateInviteDTO::from([
        'user' => '',
        'organization' => $this->organization,
        'email' => '',
        'role_id' => '',
    ]);
})->throws(ValidationException::class, 'The user field is required. (and 2 more errors)');

it('validates the rules', function () {
    $rules = CreateInviteDTO::rawRules($this->organization->id);
    expect($rules['email'][0])->toBe('required');
    expect($rules['email'][1])->toBe('email');
    expect($rules['email'][2])->toBeInstanceOf(Unique::class);
    expect($rules['role_id'][0])->toBe('required');
    expect($rules['role_id'][1])->toBeInstanceOf(Exists::class);
});
