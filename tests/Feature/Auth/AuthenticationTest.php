<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));
});

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $response = $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', ['organization' => $this->organization->slug], absolute: false));
});

test('users are redirected to create organization', function () {
    $user = User::factory()->create();
    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('welcome.organization', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $this->user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users gets locked after limit request', function () {
    $response = null;
    for ($i = 0; $i < 6; $i++) {
        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'wrong-password',
        ]);
        sleep(1 / 300);
    }

    $response->assertSessionHasErrors([
        'email' => 'Too many login attempts. Please try again in 59 seconds.',
    ]);
});

test('users can logout', function () {
    $response = $this->actingAs($this->user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
