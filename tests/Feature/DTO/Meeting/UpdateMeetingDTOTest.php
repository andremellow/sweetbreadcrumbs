<?php

use App\DTO\Meeting\CreateMeetingDTO;
use App\DTO\Meeting\UpdateMeetingDTO;
use Illuminate\Validation\ValidationException;

covers(UpdateMeetingDTO::class);

it('validates required fields', function () {
    UpdateMeetingDTO::from(['name' => '']);
})->throws(ValidationException::class, 'The project field is required. (and 4 more errors)');

it('validates the rules', function () {
    expect(UpdateMeetingDTO::rules())->toBe([
        'meeting_id' => ['required', 'integer'],
        ...CreateMeetingDTO::rules(),
    ]);
});
