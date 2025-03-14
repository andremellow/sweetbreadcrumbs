<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Livewire\Project\ProjectModal;
use App\Models\Project;
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

    // Create test projects
    $this->project = Project::factory()->for($this->organization)->withPriority($this->organization)->create();

    URL::defaults(['organization' => $this->organization->slug]);
});

afterEach(function () {
    Mockery::close();
});

it('renders the ProjectModal component successfully', function () {
    Livewire::actingAs($this->user)
        ->test(ProjectModal::class, [
            'organization' => $this->organization,
        ])
        ->assertStatus(200)
        ->assertSee('Keep on track')
        ->assertSee('Project Name')
        ->assertSee('Priority')
        ->assertSeeHtml('wire:model="form.name"')
        ->assertSeeHtml('wire:model.live="priorityId"')
        ->assertSeeHtml('data-modal="project-form-modal"')
        ->assertSeeHtml('Save');
});

it('loads a project', function () {
    Livewire::actingAs($this->user)
        ->test(ProjectModal::class, [
            'organization' => $this->organization,
        ])
        ->call('load', $this->project->id)
        ->assertSet('form.name', $this->project->name)
        ->assertSet('form.priority_id', $this->project->priority_id)
        ->assertSet('showProjectFormModal', true);
});

it('resets the form if a project id null is given', function () {
    Livewire::actingAs($this->user)
        ->test(ProjectModal::class, [
            'organization' => $this->organization,
        ])
        ->call('load', $this->project->id)
        ->assertSet('form.name', $this->project->name)
        ->call('load', null)
        ->assertSet('form.id', null)
        ->assertSet('form.name', '');
});

it('is listeing for load-project-form-modal event', function () {
    $projectModal = Livewire::actingAs($this->user)
        ->test(ProjectModal::class, [
            'organization' => $this->organization,
        ]);

    $projectModal
        ->dispatch('load-project-form-modal', projectId: $this->project->id)
        ->assertSet('form.id', $this->project->id)
        ->assertSet('form.name', $this->project->name)
        ->assertSet('form.priority_id', $this->project->priority_id)
        ->assertSet('showProjectFormModal', true);
});

it('resets form when modal is closed', function () {
    Livewire::actingAs($this->user)
        ->test(ProjectModal::class, [
            'organization' => $this->organization,
        ])
        ->set('form.id', $this->project->id)
        ->set('form.name', $this->project->name)
        ->set('form.priority_id', $this->project->priority_id)
        ->call('onModalClose')
        ->assertSet('form.id', null)
        ->assertSet('form.name', '')
        ->assertSet('form.priority_id', null);
});

it('validates', function () {

    Livewire::actingAs($this->user)
        ->test(ProjectModal::class, [
            'organization' => $this->organization,
        ])
        ->call('save')
        ->assertHasErrors([
            'form.name' => ['The name field is required.'],
            'form.priority_id' => ['The priority id field is required.'],
        ]);
});

it('created a project', function () {
    $project = $this->organization->projects()->where('name', 'New Project Name 123')->first();
    expect($project)->toBeNull();

    Livewire::actingAs($this->user)
        ->test(ProjectModal::class, [
            'organization' => $this->organization,
        ])

        ->set('form.name', 'New Project Name 123')
        ->set('form.priority_id', $this->priorities[0])
        ->call('save');

    $project = $this->organization->projects()->where('name', 'New Project Name 123')->first();

    expect($project->name)->toBe('New Project Name 123');
    expect($project->priority_id)->toBe($this->priorities[0]);
});

it('updates a project', function () {
    Livewire::actingAs($this->user)
        ->test(ProjectModal::class, [
            'organization' => $this->organization,
        ])
        ->call('load', projectId: $this->project->id)
        ->set('form.name', 'New Project Name 123')
        ->set('form.priority_id', $this->priorities[3])
        ->call('save');

    $this->project->refresh();

    expect($this->project->name)->toBe('New Project Name 123');
    expect($this->project->priority_id)->toBe($this->priorities[3]);
});
