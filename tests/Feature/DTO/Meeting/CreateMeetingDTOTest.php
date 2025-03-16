<?php

use App\DTO\Meeting\CreateMeetingDTO;
use Illuminate\Validation\ValidationException;

covers(CreateMeetingDTO::class);

it('validates required fields', function () {
    CreateMeetingDTO::from(['name' => '']);
})->throws(ValidationException::class, 'The workstream field is required. (and 3 more errors)');

it('validates the rules', function () {
    expect(CreateMeetingDTO::rules())->toBe([
        'name' => ['required', 'string', 'min:2', 'max:50'],
        'description' => ['required', 'string', 'min:2'],
        'date' => ['required', 'date'],
    ]);
});
