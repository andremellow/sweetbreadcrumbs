<?php

use App\Enums\EventEnum;
use App\Livewire\Meeting\MeetingModal;
use App\Models\Meeting;
use App\Models\User;
use App\Models\Workstream;
use Carbon\Carbon;
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

it('renders the MeetingModal component successfully', function () {
    Livewire::actingAs($this->user)
        ->test(MeetingModal::class, ['workstream' => $this->workstream])
        ->assertStatus(200)
        ->assertSee('Log It Before You Forget It—Because Meetings Deserve a Paper Trail!')
        ->assertSee($this->workstream->name)
        ->assertSee('Name')
        ->assertSee('Description')
        ->assertSee('Meeting date')
        ->assertSeeHtml('wire:model="form.name"')
        ->assertSeeHtml('wire:model="form.description"')
        ->assertSeeHtml('wire:model.self="form.date"')
        ->assertSeeHtml('data-modal="meeting-form-modal"')
        ->assertSeeHtml('Save');
});

it('loads a meeting', function () {
    Livewire::actingAs($this->user)
        ->test(MeetingModal::class, [
            'workstream' => $this->workstream,
        ])
        ->call('load', $this->meeting->id)
        ->assertSet('form.id', $this->meeting->id)
        ->assertSet('form.name', $this->meeting->name)
        ->assertSet('form.description', $this->meeting->description)
        ->assertSet('form.date', function ($date) {
            return $date->toDateString() === $this->meeting->date->toDateString();
        })
        ->assertSet('showMeetingFormModal', true);
});

it('resets the form if a meeting id null is given', function () {
    Livewire::actingAs($this->user)
        ->test(MeetingModal::class, [
            'workstream' => $this->workstream,
        ])
        ->call('load', $this->meeting->id)
        ->assertSet('form.name', $this->meeting->name)
        ->call('load', null)
        ->assertSet('form.id', null)
        ->assertSet('form.name', '');
});

it('is listeing for EventEnum::LOAD_MEETING_FORM_MODAL event', function () {
    $workstreamModal = Livewire::actingAs($this->user)
        ->test(MeetingModal::class, [
            'workstream' => $this->workstream,
        ]);

    $workstreamModal
        ->dispatch(EventEnum::LOAD_MEETING_FORM_MODAL->value, meetingId: $this->meeting->id)
        ->assertSet('form.id', $this->meeting->id)
        ->assertSet('form.name', $this->meeting->name)
        ->assertSet('form.description', $this->meeting->description)
        // ->assertSet('form.date', $this->meeting->date) //  TODO: Search about Framework issue
        ->assertSet('showMeetingFormModal', true);
});

it('resets form when modal is closed', function () {
    Livewire::actingAs($this->user)
        ->test(MeetingModal::class, [
            'workstream' => $this->workstream,
        ])
        ->set('form.id', $this->meeting->id)
        ->set('form.name', $this->meeting->name)
        ->set('form.description', $this->meeting->description)
        ->set('form.date', $this->meeting->date)
        ->call('onModalClose')
        ->assertSet('form.id', null)
        ->assertSet('form.name', '')
        ->assertSet('form.description', null)
        ->assertSet('form.date', null);
});

it('validates', function () {

    Livewire::actingAs($this->user)
        ->test(MeetingModal::class, [
            'workstream' => $this->workstream,
        ])
        ->call('save')
        ->assertHasErrors([
            'form.name' => ['The name field is required.'],
            'form.description' => ['The description field is required.'],
            'form.date' => ['The date field is required.'],
        ]);
});

it('created a meeting', function () {
    $meeting = $this->workstream->meetings()->where('name', 'New Meeting Name 123')->first();
    expect($meeting)->toBeNull();

    $date = Carbon::now();
    Livewire::actingAs($this->user)
        ->test(MeetingModal::class, [
            'workstream' => $this->workstream,
        ])
        ->set('form.name', 'New Meeting Name 123')
        ->set('form.description', 'Description of the meeting')
        ->set('form.date', $date)
        ->call('save');

    $meeting = $this->workstream->meetings()->where('name', 'New Meeting Name 123')->first();

    expect($meeting->name)->toBe('New Meeting Name 123');
    expect($meeting->description)->toBe('Description of the meeting');
    expect($meeting->date->toDateString())->toBe($date->toDateString());
});

it('updates a meeting', function () {
    $date = Carbon::now();
    Livewire::actingAs($this->user)
        ->test(MeetingModal::class, [
            'workstream' => $this->workstream,
        ])
        ->call('load', meetingId: $this->meeting->id)
        ->set('form.name', 'New Meeting Name 123')
        ->set('form.description', 'this is the new description')
        ->set('form.date', $date)
        ->call('save');

    $this->meeting->refresh();

    expect($this->meeting->name)->toBe('New Meeting Name 123');
    expect($this->meeting->description)->toBe('this is the new description');
    expect($this->meeting->date->toDateString())->toBe($date->toDateString());
});
