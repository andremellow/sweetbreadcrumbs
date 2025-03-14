<?php

use App\DTO\Task\CreateTaskDTO;
use Illuminate\Validation\ValidationException;

covers(CreateTaskDTO::class);

it('validates required fields', function () {
    CreateTaskDTO::from(['user' => '']);
})->throws(ValidationException::class, 'The user field is required. (and 3 more errors)');

it('validates the rules', function () {
    expect(CreateTaskDTO::rules())->toBe([
        'name' => ['required'],
        'description' => ['nullable', 'string'],
        'priority_id' => ['required', 'integer'],
        'due_date' => ['nullable', 'date'],
    ]);
});
