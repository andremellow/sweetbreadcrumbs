<?php

namespace App\Handlers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class RouteParameterHandler
{
    protected ?string $slug;

    public function __construct(protected Request $request)
    {
        $this->slug = $this->maybeExtractSlug($request->route('organization'));
    }

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @param mixed                                                                            $request
     */
    public function handle(): bool
    {

        /**
         * HERE, ALL REQUEST SHOULD HAVE A SLUG THAT BELONGS TO THE USER
         * OR A VALID SESSION.
         */

        // Should have slug or a session
        if ($this->slugIsNull() && $this->missingSession()) {
            abort(403, 'Invalid organization');
        }

        /**
         * Because of livewire, the component update does not cara the organization on the url
         * In that case, it uses the session, if present.
         */
        if ($this->slugIsNull() && $this->hasSession()) {
            return $this->setParameters($this->getSession());
        }

        if ($this->slugPresent()) {
            /**
             * If slug did not change, just use session session.
             * For securty reason, this maybe remove, what would cause
             * Check if the user belongs to the organization every request.
             */
            if ($this->slugDidNotChange()) {
                return $this->setParameters($this->getSession());
            }

            /**
             * Slug changed!
             * It will make a select to check if the user belongs to the organization.
             */

            return $this->setParameters($this->getOrganizationFromSlug());

        }

        // IT SHOULD NOT GET HERE. I CAN'T EVENT TEST THIS. IGNORING THE COVERAGE
        // @codeCoverageIgnoreStart
        abort(403, 'Invalid organization slug');
        // @codeCoverageIgnoreEnd
    }

    public function shouldSkip()
    {
        return Route::currentRouteName() === 'livewire.update' &&
                $this->slug === null &&
                $this->hasSession() === false;
    }

    public function slugIsNull(): bool
    {
        return $this->slug === null;
    }

    public function slugPresent(): bool
    {
        return ! $this->slugIsNull();
    }

    protected function getSession(): Organization
    {
        return $this->request->session()->get('current_organization');
    }

    protected function hasSession(): bool
    {
        return $this->request->session()->has('current_organization');
    }

    protected function missingSession(): bool
    {
        return ! $this->hasSession();
    }

    protected function slugDidNotChange(): bool
    {
        if ($this->missingSession()) {
            return false;
        }

        return $this->slug === $this->request->session()->get('current_organization')->slug;
    }

    protected function maybeExtractSlug(string|Organization|null $organizationSlug): ?string
    {
        if ($organizationSlug instanceof Organization) {
            return $organizationSlug->slug;
        }

        return $organizationSlug;
    }

    protected function getOrganizationFromSlug(): Organization
    {
        $organization = Auth::user()->organizations()->whereSlug($this->slug)->first();

        if (! $organization) {
            abort(403, 'You don\'t have access to this organization');
        }

        return $organization;
    }

    protected function setParameters(Organization $organization): bool
    {
        $this->request->session()->put('current_organization', $organization);
        Context::add('current_organization', $organization);

        URL::defaults(['organization' => $organization->slug]);
        View::share('currentOrganizationSlug', $organization->slug);

        return true;
    }
}
