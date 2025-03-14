<?php

use App\DTO\Organization\CreateOrganizationDTO;
use Illuminate\Validation\ValidationException;

covers(CreateOrganizationDTO::class);

it('validates required fields', function () {
    CreateOrganizationDTO::from(['name' => '']);
})->throws(ValidationException::class, 'The name field is required.');

it('validates the rules', function () {
    expect(CreateOrganizationDTO::rules())->toBe([
        'name' => ['required', 'string', 'min:2', 'max:50'],
    ]);
});
