<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Livewire\Organization\ListTasksCard;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\MeetingService;
use App\Services\OrganizationService;
use App\Services\TaskService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('new organization'));
    $this->project = Project::factory()->for($this->organization)->withPriority($this->organization)->create();
    $this->tasks = Task::factory(2)->for($this->project, 'taskable')->withPriority($this->organization)->create();
    Task::factory(5)->for($this->project, 'taskable')->withPriority($this->organization)->create();

    URL::defaults(['organization' => $this->organization->slug]);
    View::share('currentOrganizationSlug', $this->organization->slug);

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

it('renders the ListTasksCard component successfully', function () {
    Livewire::actingAs($this->user)
        ->test(ListTasksCard::class)
        ->assertStatus(200);
});

it('loads meeting card', function () {
    $taskService = app(TaskService::class);

    $tasks = $taskService->listForCard(
        taskable: $this->project,
        pageSize: 5
    );

    Livewire::actingAs($this->user)
        ->test(ListTasksCard::class)
        ->assertSee($tasks[0]->name)
        ->assertSee($tasks[0]->priority->name)
        ->assertSee($tasks[0]->taskable->identification)
        ->assertSee($tasks[0]->due_date->format('m/d/Y'))
        ->assertSee($tasks[1]->name)
        ->assertSee($tasks[2]->name)
        ->assertSee($tasks[3]->name)
        ->assertSee($tasks[4]->name);
});

it('closes a task', function () {
    
    Livewire::actingAs($this->user)
        ->test(ListTasksCard::class)
        ->call('close', $this->tasks[0]->id);

    $this->tasks[0]->refresh();

    expect($this->tasks[0]->completed_at)->not->toBeNull();
});
