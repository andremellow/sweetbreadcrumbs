<?php

namespace App\Http\Controllers;

use App\DTO\Meeting\CreateMeetingDTO;
use App\DTO\Meeting\DeleteMeetingDTO;
use App\DTO\Meeting\UpdateMeetingDTO;
use App\Enums\SortDirection;
use App\Http\Requests\Meeting\CreateMeetingRequest;
use App\Http\Requests\Meeting\UpdateMeetingRequest;
use App\Models\Meeting;
use App\Models\Organization;
use App\Models\Project;
use App\Services\MeetingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class MeetingController extends Controller
{
    public function __construct(protected MeetingService $meetingService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Organization $organization, Project $project, Request $request)
    {
        $sortBy = $request->get('sort_by', 'name');
        $name = $request->name;
        $sortDirection = SortDirection::tryFrom($request->get('sort_direction', SortDirection::DESC->value));

        $meetings = $this->meetingService->list(
            $project,
            $name,
            $sortBy,
            $sortDirection
        );

        return Inertia::render('projects/meetings', [
            'project' => $project,
            'meetings' => $meetings,
            'sortable' => [
                'sorted_by' => $sortBy,
                'sorted_direction' => $sortDirection->value,
            ],
            'filters' => [
                'name' => $name,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Organization $organization, Project $project, CreateMeetingRequest $request)
    {
        $this->meetingService->create(
            $request->user(),
            CreateMeetingDTO::from([
                'project' => $project,
                ...$request->validated(),
            ])
        );

        session()->flash('success', 'Meeting created');

        return Redirect::route('projects.meetings.index', ['organization' => $organization->slug, 'project' => $project->id]);
    }

    /**
     * Return resource to get edit.
     */
    public function edit(Organization $organization, Project $project, Meeting $meeting, Request $request)
    {
        return Inertia::render('projects/meetings', [
            'meeting' => $meeting,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Organization $organization, Project $project, Meeting $meeting, UpdateMeetingRequest $request)
    {
        $this->meetingService->update(
            $request->user(),
            UpdateMeetingDTO::from([
                'project' => $project,
                'meeting_id' => $meeting->id,
                ...$request->validated(),
            ])
        );

        session()->flash('success', 'Meeting updated');

        return Redirect::route('projects.meetings.index', ['organization' => $organization->slug, 'project' => $project->id, ...$request->get('redirect_parameters', [])]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization, Project $project, Meeting $meeting, Request $request)
    {
        $this->meetingService->delete(
            $request->user(),
            new DeleteMeetingDTO($meeting)
        );

        session()->flash('success', 'Meeting deleted');

        return Redirect::route('projects.meetings.index', ['organization' => $organization->slug, 'project' => $project->id]);
    }
}
