<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Livewire\Project\Dashboard;
use App\Livewire\Project\ListMeetingsCard;
use App\Livewire\Project\ListTasksCard;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;



beforeEach(function () {
    // Create test user and organization
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('new organization'));
    $this->project = Project::factory()->for($this->organization)->withPriority($this->organization)->create();

    URL::defaults(['organization' => $this->organization->slug]);
    View::share('currentOrganizationSlug', $this->organization->slug);

});

afterEach(function () {
    Mockery::close();
});

it('renders the Dashboard component successfully', function () {
    
    Livewire::actingAs($this->user)
        ->test(Dashboard::class, [
            'project' => $this->project,
        ])
        ->assertSeeLivewire(ListMeetingsCard::class)
        ->assertSeeLivewire(ListTasksCard::class);
});
