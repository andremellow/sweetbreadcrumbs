<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use App\Services\UserService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class SetOrganizationRouteParameter
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @param mixed                                                                            $request
     */
    public function handle($request, Closure $next)
    {
        // Get the organization from the current route
        $organization = $request->route('organization');

        if ($organization == null) {
            $organization = app(UserService::class)->getCurrentOrganization()->slug;
        } elseif ($organization instanceof Organization) {
            $organization = $organization->slug;
        }

        if ($organization) {
            // Ensure the 'organization' is always added to route() calls
            URL::defaults(['organization' => $organization]);
            View::share('currentOrganizationSlug', $organization);

        }

        return $next($request);
    }
}
