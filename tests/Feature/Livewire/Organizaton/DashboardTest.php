<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Livewire\Organization\Dashboard;
use App\Livewire\Organization\ListMeetingsCard;
use App\Livewire\Organization\ListTasksCard;
use App\Models\User;
use App\Services\OrganizationService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('new organization'));

    URL::defaults(['organization' => $this->organization->slug]);
    View::share('currentOrganizationSlug', $this->organization->slug);

    app()->bind(OrganizationService::class, function () {
        return new OrganizationService(
            app(CreateOrganization::class),
            $this->organization
        );
    });
});

it('renders the Dashboard component successfully', function () {

    Livewire::actingAs($this->user)
        ->test(Dashboard::class)
        ->assertSeeLivewire(ListMeetingsCard::class)
        ->assertSeeLivewire(ListTasksCard::class);
});
