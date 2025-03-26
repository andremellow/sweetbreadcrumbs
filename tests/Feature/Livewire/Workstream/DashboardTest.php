<?php

use App\Livewire\Workstream\Dashboard;
use App\Livewire\Workstream\ListMeetingsCard;
use App\Livewire\Workstream\ListTasksCard;
use App\Models\User;
use App\Models\Workstream;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();

    URL::defaults(['organization' => $this->organization->slug]);
    View::share('currentOrganizationSlug', $this->organization->slug);

});

afterEach(function () {
    Mockery::close();
});

it('renders the Dashboard component successfully', function () {

    Livewire::actingAs($this->user)
        ->test(Dashboard::class, [
            'workstream' => $this->workstream,
        ])
        ->assertSeeLivewire(ListMeetingsCard::class)
        ->assertSeeLivewire(ListTasksCard::class);
});
