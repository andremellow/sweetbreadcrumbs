<?php

use App\Livewire\Meeting\MeetingViewModal;
use App\Models\Meeting;
use App\Models\User;
use App\Models\Workstream;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;

    // Create test workstreams
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();
    $this->meeting = Meeting::factory()->for($this->workstream)->create();

    URL::defaults(['organization' => $this->organization->slug]);
});

afterEach(function () {
    Mockery::close();
});

it('loads a meeting', function () {
    Livewire::actingAs($this->user)
        ->test(MeetingViewModal::class)
        ->call('load', $this->meeting->id)
        ->assertSet('id', $this->meeting->id)
        ->assertSet('name', $this->meeting->name)
        ->assertSet('description', $this->meeting->description)
        // ->assertSet('date', $this->meeting->date) TODO: Search about Framework issue
        ->assertSee($this->meeting->id)
        ->assertSee($this->meeting->name)
        ->assertSee($this->meeting->description)
        ->assertSee($this->meeting->date->toDateString())
        ->assertSet('showMeetingViewModal', true);
});
