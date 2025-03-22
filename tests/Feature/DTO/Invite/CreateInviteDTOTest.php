<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Invite\CreateInviteDTO;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Validation\ValidationException;

covers(CreateInviteDTO::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));
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
    $rules = CreateInviteDTO::rawRules($this->organization);
    expect($rules['email'][0])->toBe('required');
    expect($rules['email'][1])->toBe('email');
    expect($rules['email'][2])->toBeInstanceOf(Unique::class);
    expect($rules['role_id'][0])->toBe('required');
    expect($rules['role_id'][1])->toBeInstanceOf(Exists::class);
});
