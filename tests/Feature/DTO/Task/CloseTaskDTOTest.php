<?php

use App\DTO\Task\CloseTaskDTO;
use Illuminate\Validation\ValidationException;

covers(CloseTaskDTO::class);

it('validates required fields', function () {
    CloseTaskDTO::from(['user' => '']);
})->throws(ValidationException::class, 'The user field is required. (and 1 more error)');

it('validates the rules', function () {
    expect(CloseTaskDTO::rules())->toBe([
        'task_id' => ['required'],
    ]);
});
