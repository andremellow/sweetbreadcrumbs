<?php

use App\Enums\EventEnum;
use App\Enums\SortDirection;
use App\Livewire\Meeting\ListMeetings;
use App\Models\Meeting;
use App\Models\User;
use App\Models\Workstream;
use App\Services\MeetingService;
use Flux\DateRange;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;

beforeEach(function () {
    // Create test user and organization
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;
    $this->app['session']->start();
    session(['current_organization_id' => $this->organization->id]);

    // Create test workstreams
    $this->workstream = Workstream::factory()->for($this->organization)->withPriority($this->organization)->create();
    $this->meetings = Meeting::factory(3)->for($this->workstream)->create();

    URL::defaults(['organization' => $this->organization->slug]);
    View::share('currentOrganizationSlug', $this->organization->slug);
});

it('renders the ListMeetings component successfully', function () {
    Livewire::actingAs($this->user)
        ->test(ListMeetings::class, ['workstream' => $this->workstream])
        ->assertStatus(200);
});

it('lists meetings correctly', function () {
    Livewire::actingAs($this->user)
        ->test(ListMeetings::class, ['workstream' => $this->workstream])
        ->assertSee($this->meetings[0]->name)
        ->assertSee($this->meetings[1]->name)
        ->assertSee($this->meetings[2]->name);
});

it('filters meetings by name', function () {
    Livewire::actingAs($this->user)
        ->test(ListMeetings::class, ['workstream' => $this->workstream])
        ->set('search', $this->meetings[1]->name)
        ->assertSee($this->meetings[1]->name)
        ->assertDontSee($this->meetings[0]->name)
        ->assertDontSee($this->meetings[2]->name);
});

it('resets the filter values when resetForm is called', function () {

    Livewire::actingAs($this->user)
        ->test(ListMeetings::class, ['workstream' => $this->workstream])
        ->set('search', 'Filtered Workstream')
        // ->set('dateRange', new DateRange(now()->subMonth(), now()))
        ->call('resetForm')
        ->assertSet('search', null)
        ->assertSet('dateRange', null);
});

it('resets the filter values when reset event dispatched', function () {

    $component = Livewire::actingAs($this->user)
        ->test(ListMeetings::class, ['workstream' => $this->workstream])
        ->set('search', 'Filtered Workstream');

    $component
        ->dispatch(EventEnum::RESET->value)
        ->assertSet('name', null);
});

it('sets sort column and direction', function () {
    // TODO: Maybe move this to a diffent method and test only the WithSort Trait
    Livewire::actingAs($this->user)
        ->test(ListMeetings::class, ['workstream' => $this->workstream])
        ->set('sortBy', 'name') // Set sortBy Column
        ->assertSet('sortBy', 'name') // No secret, it should sorted
        ->call('sort', 'date') // Call sort method
        ->assertSet('sortBy', 'date') // It should set sortBy
        ->assertSet('sortDirection', SortDirection::ASC) // And because it is a new column, set it to ASK
        ->call('sort', 'date') // Sort the same column again
        ->assertSet('sortBy', 'date') // sortBy should be the same
        ->assertSet('sortDirection', SortDirection::DESC); // Should set it to DESC
});

it('deletes a meeting successfully and dispatches event', function () {
    $meetingToDelete = $this->meetings->first();

    Livewire::actingAs($this->user)
        ->test(ListMeetings::class, ['workstream' => $this->workstream])
        ->call('delete', app(MeetingService::class), $meetingToDelete->id)
        ->assertDispatched(EventEnum::MEETING_DELETED->value, meetingId: $meetingToDelete->id);

    $meetingToDelete->refresh();

    expect($meetingToDelete->deleted_at)->not->toBeNull();
});

it('applyFilter reload with right data', function () {
    Livewire::actingAs($this->user)
        ->test(ListMeetings::class, ['workstream' => $this->workstream])
        ->set('search', $this->meetings[1]->name)
        ->call('applyFilter')
        ->assertSee($this->meetings[1]->name)
        ->assertDontSee($this->meetings[0]->name)
        ->assertDontSee($this->meetings[2]->name);
});
