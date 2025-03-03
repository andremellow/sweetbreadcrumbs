<?php

namespace App\Http\Controllers;

use App\DTO\Project\CreateProjectDTO;
use App\DTO\Project\UpdateProjectDTO;
use App\Enums\SortDirection;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Organization;
use App\Models\Project;
use App\Services\OrganizationService;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function __construct(protected ProjectService $projectService) {}

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
                'sorted_direction' => $sortDirection->value,
            ],
            'filters' => [
                'name' => $name,
                'priority_id' => $priorityId,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Organization $organization)
    {
        return Inertia::render('projects/create-project');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Organization $organization, StoreProjectRequest $request)
    {
        $project = $this->projectService->create(
            $request->user,
            CreateProjectDTO::from(['organization' => $organization, ...$request->validated()])
        );

        session()->flash('success', 'Project created');

        return Redirect::route('projects.dashboard', ['organization' => $organization->slug, 'project' => $project->id]);
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
            'releases' => $organizationService->getReleasesDropDownData(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Organization $organization, UpdateProjectRequest $request, Project $project)
    {

        $project = $this->projectService->update(
            $request->user(),
            UpdateProjectDTO::from([
                'organization' => $organization,
                'project_id' => $project->id,
                ...$request->validated()])
        );

        session()->flash('success', 'Project update');

        return Redirect::route('projects.dashboard', ['organization' => $organization->slug, 'project' => $project->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
