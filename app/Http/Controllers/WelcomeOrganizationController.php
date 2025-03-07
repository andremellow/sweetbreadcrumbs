<?php

namespace App\Http\Controllers;

use App\DTO\Organization\CreateOrganizationDTO;
use App\Http\Requests\StoreOrganizationRequest;
use App\Services\OrganizationService;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class WelcomeOrganizationController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('welcome/organization');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrganizationService $organizationService, StoreOrganizationRequest $request)
    {
        $organization = $organizationService->create(
            $request->user(),
            CreateOrganizationDTO::from($request->validated())
        );

        session()->flash('success', 'Organization created');

        return Redirect::route('dashboard', ['organization' => $organization->slug]);
    }
}
