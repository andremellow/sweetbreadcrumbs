<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Livewire\Meeting\MeetingViewModal;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('new organization'));

    // Create test projects
    $this->project = Project::factory()->for($this->organization)->withPriority($this->organization)->create();
    $this->meeting = Meeting::factory()->for($this->project)->create();

    URL::defaults(['organization' => $this->organization->slug]);
});

afterEach(function () {
    Mockery::close();
});

it('it loads a meeting', function () {
    Livewire::actingAs($this->user)
        ->test(MeetingViewModal::class)
        ->call('load', $this->meeting->id)
        ->assertSet('id', $this->meeting->id)
        ->assertSet('name', $this->meeting->name)
        ->assertSet('description', $this->meeting->description)
        ->assertSet('date', $this->meeting->date)
        ->assertSee($this->meeting->id)
        ->assertSee($this->meeting->name)
        ->assertSee($this->meeting->description)
        ->assertSee($this->meeting->date->toDateString())
        ->assertSet('showMeetingViewModal', true);
});
