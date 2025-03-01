<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Project;
use Inertia\Inertia;

class ProjectDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Organization $organization, Project $project)
    {
        return Inertia::render('projects/dashboard', [
            'project' => $project,
        ]);
    }
}
