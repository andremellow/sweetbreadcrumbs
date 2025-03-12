<?php

use App\Models\User;

it('updates a meeting with new values', function () {
    $this->user = User::factory()->create(['first_name' => 'John', 'last_name' => 'Doe Doe']);
    expect($this->user->initials())->toBe('JDD');
});
