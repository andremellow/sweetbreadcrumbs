<?php

use App\DTO\Task\OpenTaskDTO;
use Illuminate\Validation\ValidationException;

covers(OpenTaskDTO::class);

it('validates required fields', function () {
    OpenTaskDTO::from(['user' => '']);
})->throws(ValidationException::class, 'The user field is required. (and 1 more error)');

it('validates the rules', function () {
    expect(OpenTaskDTO::rules())->toBe([
        'task_id' => ['required'],
    ]);
});
