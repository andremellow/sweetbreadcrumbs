<?php

use App\DTO\Task\DeleteTaskDTO;
use Illuminate\Validation\ValidationException;

covers(DeleteTaskDTO::class);

it('validates required fields', function () {
    DeleteTaskDTO::from([]);
})->throws(ValidationException::class, 'The user field is required. (and 1 more error)');

it('validates the rules', function () {
    expect(DeleteTaskDTO::rules())->toBe([
        'task_id' => ['required', 'integer'],
    ]);
});
