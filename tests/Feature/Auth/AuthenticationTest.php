<?php

use App\Actions\CreateOrganization;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = app(CreateOrganization::class)($this->user, 'New Organization Name');
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

test('users can logout', function () {
    $response = $this->actingAs($this->user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
