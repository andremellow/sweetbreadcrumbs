<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Livewire\Meeting\MeetingModal;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
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

it('renders the MeetingModal component successfully', function () {
    Livewire::actingAs($this->user)
        ->test(MeetingModal::class, ['project' => $this->project])
        ->assertStatus(200)
        ->assertSee('Log It Before You Forget It—Because Meetings Deserve a Paper Trail!')
        ->assertSee($this->project->name)
        ->assertSee('Name')
        ->assertSee('Description')
        ->assertSee('Meeting date')
        ->assertSeeHtml('wire:model="form.name"')
        ->assertSeeHtml('wire:model="form.description"')
        ->assertSeeHtml('wire:model.self="form.date"')
        ->assertSeeHtml('data-modal="meeting-form-modal"')
        ->assertSeeHtml('Save');
});

it('it loads a meeting', function () {
    Livewire::actingAs($this->user)
        ->test(MeetingModal::class, [
            'project' => $this->project,
        ])
        ->call('load', $this->meeting->id)
        ->assertSet('form.id', $this->meeting->id)
        ->assertSet('form.name', $this->meeting->name)
        ->assertSet('form.description', $this->meeting->description)
        ->assertSet('form.date', $this->meeting->date)
        ->assertSet('showMeetingFormModal', true);
});

it('it resets the form if a meeting id null is given', function () {
    Livewire::actingAs($this->user)
        ->test(MeetingModal::class, [
            'project' => $this->project,
        ])
        ->call('load', $this->meeting->id)
        ->assertSet('form.name', $this->meeting->name)
        ->call('load', null)
        ->assertSet('form.id', null)
        ->assertSet('form.name', '');
});

it('it is listeing for load-meeting-form-modal event', function () {
    $projectModal = Livewire::actingAs($this->user)
        ->test(MeetingModal::class, [
            'project' => $this->project,
        ]);

    $projectModal
        ->dispatch('load-meeting-form-modal', meetingId: $this->meeting->id)
        ->assertSet('form.id', $this->meeting->id)
        ->assertSet('form.name', $this->meeting->name)
        ->assertSet('form.description', $this->meeting->description)
        ->assertSet('form.date', $this->meeting->date)
        ->assertSet('showMeetingFormModal', true);
});

it('it resets form when modal is closed', function () {
    Livewire::actingAs($this->user)
        ->test(MeetingModal::class, [
            'project' => $this->project,
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

it('it validates', function () {

    Livewire::actingAs($this->user)
        ->test(MeetingModal::class, [
            'project' => $this->project,
        ])
        ->call('save')
        ->assertHasErrors([
            'form.name' => ['The name field is required.'],
            'form.description' => ['The description field is required.'],
            'form.date' => ['The date field is required.'],
        ]);
});

it('created a meeting', function () {
    $meeting = $this->project->meetings()->where('name', 'New Meeting Name 123')->first();
    expect($meeting)->toBeNull();

    $date = Carbon::now();
    Livewire::actingAs($this->user)
        ->test(MeetingModal::class, [
            'project' => $this->project,
        ])
        ->set('form.name', 'New Meeting Name 123')
        ->set('form.description', 'Description of the meeting')
        ->set('form.date', $date)
        ->call('save');

    $meeting = $this->project->meetings()->where('name', 'New Meeting Name 123')->first();

    expect($meeting->name)->toBe('New Meeting Name 123');
    expect($meeting->description)->toBe('Description of the meeting');
    expect($meeting->date->toDateString())->toBe($date->toDateString());
});

it('it updates a meeting', function () {
    $date = Carbon::now();
    Livewire::actingAs($this->user)
        ->test(MeetingModal::class, [
            'project' => $this->project,
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
