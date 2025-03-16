<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Livewire\Workstream\WorkstreamModal;
use App\Models\Workstream;
use App\Models\User;
use App\Services\OrganizationService;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('new organization'));
    $this->priorities = app(OrganizationService::class)
        ->setOrganization($this->organization)
        ->getPrioritiesDropDownData()
        ->keys();

    // Create test workstreams
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();

    URL::defaults(['organization' => $this->organization->slug]);

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

it('renders the WorkstreamModal component successfully', function () {
    Livewire::actingAs($this->user)
        ->test(WorkstreamModal::class, [
            'organization' => $this->organization,
        ])
        ->assertStatus(200)
        ->assertSee('Keep on track')
        ->assertSee('Workstream Name')
        ->assertSee('Priority')
        ->assertSeeHtml('wire:model="form.name"')
        ->assertSeeHtml('wire:model.live="priorityId"')
        ->assertSeeHtml('data-modal="workstream-form-modal"')
        ->assertSeeHtml('Save');
});

it('loads a workstream', function () {
    Livewire::actingAs($this->user)
        ->test(WorkstreamModal::class, [
            'organization' => $this->organization,
        ])
        ->call('load', $this->workstream->id)
        ->assertSet('form.name', $this->workstream->name)
        ->assertSet('form.priority_id', $this->workstream->priority_id)
        ->assertSet('showWorkstreamFormModal', true);
});

it('resets the form if a workstream id null is given', function () {
    Livewire::actingAs($this->user)
        ->test(WorkstreamModal::class, [
            'organization' => $this->organization,
        ])
        ->call('load', $this->workstream->id)
        ->assertSet('form.name', $this->workstream->name)
        ->call('load', null)
        ->assertSet('form.id', null)
        ->assertSet('form.name', '');
});

it('is listeing for load-workstream-form-modal event', function () {
    $workstreamModal = Livewire::actingAs($this->user)
        ->test(WorkstreamModal::class, [
            'organization' => $this->organization,
        ]);

    $workstreamModal
        ->dispatch('load-workstream-form-modal', workstreamId: $this->workstream->id)
        ->assertSet('form.id', $this->workstream->id)
        ->assertSet('form.name', $this->workstream->name)
        ->assertSet('form.priority_id', $this->workstream->priority_id)
        ->assertSet('showWorkstreamFormModal', true);
});

it('resets form when modal is closed', function () {
    Livewire::actingAs($this->user)
        ->test(WorkstreamModal::class, [
            'organization' => $this->organization,
        ])
        ->set('form.id', $this->workstream->id)
        ->set('form.name', $this->workstream->name)
        ->set('form.priority_id', $this->workstream->priority_id)
        ->call('onModalClose')
        ->assertSet('form.id', null)
        ->assertSet('form.name', '')
        ->assertSet('form.priority_id', null);
});

it('validates', function () {

    Livewire::actingAs($this->user)
        ->test(WorkstreamModal::class, [
            'organization' => $this->organization,
        ])
        ->call('save')
        ->assertHasErrors([
            'form.name' => ['The name field is required.'],
            'form.priority_id' => ['The priority id field is required.'],
        ]);
});

it('created a workstream', function () {
    $workstream = $this->organization->workstreams()->where('name', 'New Workstream Name 123')->first();
    expect($workstream)->toBeNull();

    Livewire::actingAs($this->user)
        ->test(WorkstreamModal::class, [
            'organization' => $this->organization,
        ])

        ->set('form.name', 'New Workstream Name 123')
        ->set('form.priority_id', $this->priorities[0])
        ->call('save');

    $workstream = $this->organization->workstreams()->where('name', 'New Workstream Name 123')->first();

    expect($workstream->name)->toBe('New Workstream Name 123');
    expect($workstream->priority_id)->toBe($this->priorities[0]);
});

it('updates a workstream', function () {
    Livewire::actingAs($this->user)
        ->test(WorkstreamModal::class, [
            'organization' => $this->organization,
        ])
        ->call('load', workstreamId: $this->workstream->id)
        ->set('form.name', 'New Workstream Name 123')
        ->set('form.priority_id', $this->priorities[3])
        ->call('save');

    $this->workstream->refresh();

    expect($this->workstream->name)->toBe('New Workstream Name 123');
    expect($this->workstream->priority_id)->toBe($this->priorities[3]);
});
