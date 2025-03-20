<?php

use App\DTO\Invite\UpdateInviteSentDTO;
use Illuminate\Validation\ValidationException;

covers(UpdateInviteSentDTO::class);

it('validates required fields', function () {
    UpdateInviteSentDTO::from(['user' => '']);
})->throws(ValidationException::class, 'The user field is required. (and 2 more errors)');

it('validates the rules', function () {
    expect(UpdateInviteSentDTO::rules())->toBe([
        'invite_id' => ['required'],
    ]);
});
