<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

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

        if ($organization) {
            // Ensure the 'organization' is always added to route() calls
            URL::defaults(['organization' => $organization]);
        }

        return $next($request);
    }
}
