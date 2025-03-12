<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Livewire\Settings\Profile;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('new organization'));

    URL::defaults(['organization' => $this->organization->slug]);
    View::share('currentOrganizationSlug', $this->organization->slug);
});

it('renders the Profile component successfully', function () {
    Livewire::actingAs($this->user)
        ->test(Profile::class)
        ->assertStatus(200)
        ->assertSet('first_name', $this->user->first_name)
        ->assertSet('last_name', $this->user->last_name)
        ->assertSet('email', $this->user->email);
});

it('it validates', function () {

    Livewire::actingAs($this->user)
        ->test(Profile::class)
        ->set('first_name', '')
        ->set('last_name', '')
        ->call('updateProfileInformation')
        ->assertHasErrors([
            'first_name' => ['The first name field is required.'],
        ]);
});

it('it updates', function () {
    $email = $this->user->email;
    Livewire::actingAs($this->user)
        ->test(Profile::class)
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'johndoe@gmail.com') // Should not update the email
        ->call('updateProfileInformation')
        ->assertSee('Saved');

    $this->user->refresh();

    expect($this->user->first_name)->toBe('John');
    expect($this->user->last_name)->toBe('Doe');
    expect($this->user->email)->toBe($email);
});
