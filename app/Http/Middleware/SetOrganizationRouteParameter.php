<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Route;
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
        $organizationSlug = $request->route('organization');

        if (Route::currentRouteName() === 'livewire.update' && $organizationSlug === null && $request->session()->has('current_organization') === false) {
            return $next($request);
        }

        if ($organizationSlug instanceof Organization) {
            $organizationSlug = $organizationSlug->slug;
        }

        $organization = $this->resolveOrganization($request, $organizationSlug);

        // Ensure the 'organization' is always added to route() calls
        URL::defaults(['organization' => $organization->slug]);
        View::share('currentOrganizationSlug', $organization->slug);
        Context::add('current_organization', $organization);

        return $next($request);
    }

    public function resolveOrganization($request, ?string $slug)
    {
        if ($slug === null && $request->session()->has('current_organization') === false) {
            abort(403);
        }

        if ($slug === null && $request->session()->has('current_organization')) {
            return $request->session()->get('current_organization');
        }

        if ($slug !== null) {
            if (
                $request->session()->has('current_organization') &&
                $slug === $request->session()->get('current_organization')->slug
            ) {
                return $request->session()->get('current_organization');
            }

            $organization = Auth::user()->organizations()->whereSlug($slug)->first();

            if (! $organization) {
                abort(403);
            }

            $request->session()->put('current_organization', $organization);

            return $organization;

        }

        abort(403);

    }
}
