<?php

use App\DTO\Task\UpdateTaskDTO;
use Illuminate\Validation\ValidationException;

covers(UpdateTaskDTO::class);

it('validates required fields', function () {
    UpdateTaskDTO::from(['user' => '']);
})->throws(ValidationException::class, 'The user field is required. (and 3 more errors)');

it('validates the rules', function () {
    expect(UpdateTaskDTO::rules())->toBe([
        'task_id' => ['required', 'integer'],
        'name' => ['required'],
        'description' => ['nullable', 'string'],
        'priority_id' => ['required', 'integer'],
        'due_date' => ['nullable', 'date'],
    ]);
});
