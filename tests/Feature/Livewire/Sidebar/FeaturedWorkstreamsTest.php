<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Livewire\Sidebar\FeaturedWorkstreams;
use App\Models\Workstream;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization'));
    $this->workstreams = Workstream::factory(3)->for($this->organization)->withPriority($this->organization)->create();

    $this->organization2 = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization'));
    $this->workstreams2 = Workstream::factory(3)->for($this->organization2)->withPriority($this->organization2)->create();

    URL::defaults(['organization' => $this->organization->slug]);
    View::share(['currentOrganizationSlug' => $this->organization->slug]);
});

afterEach(function () {
    Mockery::close();
});

it('renders successfully', function () {
    // Mock the OrganizationService

    Livewire::actingAs($this->user)
        ->test(FeaturedWorkstreams::class)
        ->assertViewHas('featuredWorkstreams', function ($featuredWorkstreams) {
            return count($featuredWorkstreams) === 3;
        })
        ->assertSee($this->workstreams[0]->name)
        ->assertSeeHtml('href="'.route('workstreams.dashboard', ['organization' => $this->organization->slug, 'workstream' => $this->workstreams[0]->id]).'"')
        ->assertSee($this->workstreams[1]->name)
        ->assertSeeHtml('href="'.route('workstreams.dashboard', ['organization' => $this->organization->slug, 'workstream' => $this->workstreams[1]->id]).'"')
        ->assertSee($this->workstreams[2]->name)
        ->assertSeeHtml('href="'.route('workstreams.dashboard', ['organization' => $this->organization->slug, 'workstream' => $this->workstreams[2]->id]).'"')
        ->assertDontSee($this->workstreams2[2]->name);

});
