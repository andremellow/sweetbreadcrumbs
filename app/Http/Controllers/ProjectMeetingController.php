<?php

namespace App\Http\Controllers;

use App\Enums\SortDirection;
use App\Models\Organization;
use App\Models\Project;
use App\Services\MeetingService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectMeetingController extends Controller
{
    public function __construct(protected MeetingService $meetingService)
    {

    }

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
                'sorted_direction' => $sortDirection->value
            ],
            'filters' => [
                'name' => $name
            ]
        ]);
    }
}
