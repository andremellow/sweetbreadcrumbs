<?php

use App\DTO\Invite\DeleteInviteDTO;
use Illuminate\Validation\ValidationException;

covers(DeleteInviteDTO::class);

it('validates required fields', function () {
    DeleteInviteDTO::from([]);
})->throws(ValidationException::class, 'The user field is required. (and 2 more errors)');

it('validates the rules', function () {
    expect(DeleteInviteDTO::rules())->toBe([
        'invite_id' => ['required'],
    ]);
});
