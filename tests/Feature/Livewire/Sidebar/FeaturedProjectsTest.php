<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Livewire\Sidebar\FeaturedProjects;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization'));
    $this->projects = Project::factory(3)->for($this->organization)->withPriority($this->organization)->create();

    $this->organization2 = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization'));
    $this->projects2 = Project::factory(3)->for($this->organization2)->withPriority($this->organization2)->create();

    URL::defaults(['organization' => $this->organization->slug]);
    View::share(['currentOrganizationSlug' => $this->organization->slug]);
});

afterEach(function () {
    Mockery::close();
});

it('renders successfully', function () {
    // Mock the OrganizationService

    Livewire::actingAs($this->user)
        ->test(FeaturedProjects::class)
        ->assertViewHas('featuredProjects', function ($featuredProjects) {
            return count($featuredProjects) === 3;
        })
        ->assertSee($this->projects[0]->name)
        ->assertSeeHtml('href="'.route('projects.dashboard', ['organization' => $this->organization->slug, 'project' => $this->projects[0]->id]).'"')
        ->assertSee($this->projects[1]->name)
        ->assertSeeHtml('href="'.route('projects.dashboard', ['organization' => $this->organization->slug, 'project' => $this->projects[1]->id]).'"')
        ->assertSee($this->projects[2]->name)
        ->assertSeeHtml('href="'.route('projects.dashboard', ['organization' => $this->organization->slug, 'project' => $this->projects[2]->id]).'"')
        ->assertDontSee($this->projects2[2]->name);

});
