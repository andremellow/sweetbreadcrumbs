<?php

use App\Enums\RoleEnum;
use App\Livewire\RoleDropdown;
use App\Models\User;
use App\Services\OrganizationService;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;
    URL::defaults(['organization' => $this->organization->slug]);

    Context::add('current_organization', $this->organization);
});

afterEach(function () {
    Mockery::close();
});

it('renders successfully', function () {

    Livewire::actingAs($this->user)
        ->test(RoleDropdown::class)
        ->assertSee('Admin')
        ->assertSee('Contributor')
        ->assertSee('Viewer');
});

it('renders with user successfully', function () {

    actingAs($this->user);

    $organizationService = app(OrganizationService::class);

    $user = User::Factory()->create();

    $organizationService->attachUser(
        organization: $this->organization,
        user: $user,
        roleId: RoleEnum::VIEWER->value
    );

    Livewire::actingAs($this->user)
        ->test(RoleDropdown::class, [
            'withUser' => true,
            'user' => $user,
        ])
        ->assertDontSee('Admin')
        ->assertDontSee('Contributor')
        ->assertSee('Viewer');
});
