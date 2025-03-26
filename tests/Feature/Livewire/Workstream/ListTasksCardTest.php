<?php

use App\Livewire\Workstream\ListTasksCard;
use App\Models\Task;
use App\Models\User;
use App\Models\Workstream;
use App\Services\TaskService;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();
    $this->tasks = Task::factory(10)->for($this->workstream, 'taskable')->withPriority($this->organization)->create();

    URL::defaults(['organization' => $this->organization->slug]);
    View::share('currentOrganizationSlug', $this->organization->slug);

    Context::add('current_organization', $this->organization);
});

afterEach(function () {
    Mockery::close();
});

it('renders the ListTasksCard component successfully', function () {
    Livewire::actingAs($this->user)
        ->test(ListTasksCard::class, [
            'workstream' => $this->workstream,
        ])
        ->assertStatus(200);
});

it('loads meeting card', function () {
    $taskService = app(TaskService::class);

    $tasks = $taskService->listForCard(
        taskable: $this->workstream,
        pageSize: 5
    );

    Livewire::actingAs($this->user)
        ->test(ListTasksCard::class, [
            'workstream' => $this->workstream,
        ])
        ->assertSee($tasks[0]->name)
        ->assertSee($tasks[0]->priority->name)
        ->assertSee($tasks[0]->due_date->format('m/d/Y'))
        ->assertSee($tasks[1]->name)
        ->assertSee($tasks[2]->name)
        ->assertSee($tasks[3]->name)
        ->assertSee($tasks[4]->name);
});

it('closes a task', function () {

    Livewire::actingAs($this->user)
        ->test(ListTasksCard::class, [
            'workstream' => $this->workstream,
        ])
        ->call('close', $this->tasks[0]->id);

    $this->tasks[0]->refresh();

    expect($this->tasks[0]->completed_at)->not->toBeNull();
});
