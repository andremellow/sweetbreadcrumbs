<?php

namespace App\Http\Controllers;

use App\Enums\SortDirection;
use App\Http\Requests\StoreMeetingRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Organization;
use App\Models\Project;
use App\Services\MeetingService;
use App\Services\OrganizationService;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class MeetingController extends Controller
{
    public function __construct(protected ProjectService $projectService, protected MeetingService $meetingService)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Organization $organization, OrganizationService $organizationService, Request $request)
    {
        $sortBy = $request->get('sort_by', 'name');
        $name = $request->name;
        $priorityId = $request->priority_id;
        $organizationService->setOrganization($organization);
        $sortDirection = SortDirection::tryFrom($request->get('sort_direction', SortDirection::DESC->value));

        $projects = $this->projectService->list(
            $organization,
            $name,
            $priorityId,
            $sortBy,
            $sortDirection
        );

        return Inertia::render('projects/list-project', [
            'projects' => $projects,
            'priorities' => $organizationService->getPrioritiesDropDownData(),
            'sortable' => [
                'sorted_by' => $sortBy,
                'sorted_direction' => $sortDirection->value
            ],
            'filters' => [
                'name' => $name,
                'priority_id' => $priorityId,
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Organization $organization, Project $project, StoreMeetingRequest $request)
    {
        $this->meetingService->create(
            $project,
            $request->name,
            $request->description,
            $request->date
        );

        session()->flash('success', "meeting created");
        return Redirect::route('projects.meetings', [ 'organization' => $organization->slug, 'project' => $project->id  ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organization $organization, OrganizationService $organizationService, Project $project)
    {
        $organizationService->setOrganization($organization);

        return Inertia::render('Projects/Create', [
            'project' => $project,
            'priorities' => $organizationService->getPrioritiesDropDownData(),
            'releases' => $organizationService->getReleasesDropDownData()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Organization $organization,  UpdateProjectRequest $request, Project $project)
    {

        $project = $this->projectService->update(
            $organization,
            $project->id,
            $request->name,
            intval($request->priority_id),
            $request->toggle_on_by_release_id,
            $request->release_plan,
            $request->technical_documentation,
            $request->needs_to_start_by,
            $request->needs_to_deployed_by,
        );

        session()->flash('success', "Project update");
        return Redirect::route('projects.dashboard', [ 'organization' => $organization->slug, 'project' => $project->id  ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
