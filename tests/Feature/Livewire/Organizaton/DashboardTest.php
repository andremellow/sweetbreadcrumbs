<?php

use App\Livewire\Organization\Dashboard;
use App\Livewire\Organization\ListMeetingsCard;
use App\Livewire\Organization\ListTasksCard;
use App\Models\User;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;

    URL::defaults(['organization' => $this->organization->slug]);
    View::share('currentOrganizationSlug', $this->organization->slug);

    Context::add('current_organization', $this->organization);
});

it('renders the Dashboard component successfully', function () {

    Livewire::actingAs($this->user)
        ->test(Dashboard::class)
        ->assertSeeLivewire(ListMeetingsCard::class)
        ->assertSeeLivewire(ListTasksCard::class);
});
