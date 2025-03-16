<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Livewire\Project\ListMeetingsCard;
use App\Models\Meeting;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use App\Services\MeetingService;
use App\Services\OrganizationService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('new organization'));
    $this->project = Project::factory()->for($this->organization)->withPriority($this->organization)->create();
    $this->meetings = Meeting::factory(10)->for($this->project)->create();

    URL::defaults(['organization' => $this->organization->slug]);
    View::share('currentOrganizationSlug', $this->organization->slug);

    app()->bind(OrganizationService::class, function () {
        return new OrganizationService(
            app(CreateOrganization::class),
            $this->organization
        );
    });
});

afterEach(function () {
    Mockery::close();
});

it('renders the ListMeetingsCard component successfully', function () {
    Livewire::actingAs($this->user)
        ->test(ListMeetingsCard::class, [
            'project' => $this->project,
        ])
        ->assertStatus(200);
});

it('loads meeting card', function () {
    $meetingService = app(MeetingService::class);

    $meetings = $meetingService->lastMeeings($this->project, 5);

    Livewire::actingAs($this->user)
        ->test(ListMeetingsCard::class, [
            'project' => $this->project,
        ])
        ->assertSee($meetings[0]->name)
        ->assertSee($meetings[1]->name)
        ->assertSee($meetings[2]->name)
        ->assertSee($meetings[3]->name)
        ->assertSee($meetings[4]->name);
});
