<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Livewire\Workstream\ListMeetingsCard;
use App\Models\Meeting;
use App\Models\User;
use App\Models\Workstream;
use App\Services\MeetingService;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('new organization'));
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();
    $this->meetings = Meeting::factory(10)->for($this->workstream)->create();

    URL::defaults(['organization' => $this->organization->slug]);
    View::share('currentOrganizationSlug', $this->organization->slug);

    Context::add('current_organization', $this->organization);

});

afterEach(function () {
    Mockery::close();
});

it('renders the ListMeetingsCard component successfully', function () {
    Livewire::actingAs($this->user)
        ->test(ListMeetingsCard::class, [
            'workstream' => $this->workstream,
        ])
        ->assertStatus(200);
});

it('loads meeting card', function () {
    $meetingService = app(MeetingService::class);

    $meetings = $meetingService->lastMeeings($this->workstream, 5);

    Livewire::actingAs($this->user)
        ->test(ListMeetingsCard::class, [
            'workstream' => $this->workstream,
        ])
        ->assertSee($meetings[0]->name)
        ->assertSee($meetings[1]->name)
        ->assertSee($meetings[2]->name)
        ->assertSee($meetings[3]->name)
        ->assertSee($meetings[4]->name);
});
