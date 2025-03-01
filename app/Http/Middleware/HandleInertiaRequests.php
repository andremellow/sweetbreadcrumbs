<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use App\Models\User;
use App\Services\OrganizationService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    public function __construct(protected UserService $userService, protected OrganizationService $organizationService) {}

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $this->prepareUserService($request);

        $this->organizationService->setOrganization($this->userService->getCurrentOrganization() ?? new Organization);

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'organization' => $this->userService->getCurrentOrganization()?->toArray(),
            'featuredProjects' => $this->getProjects(),
            'priorities' => fn () => $this->organizationService->getPrioritiesDropDownData(),
            'releases' => fn () => $this->organizationService->getReleasesDropDownData(),
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
        ];
    }

    protected function getProjects(): array
    {
        $projects = $this->userService->getProjects();

        return $projects ? $projects->select('id', 'name')
            ->toArray() : [];
    }

    protected function prepareUserService(Request $request): void
    {
        $user = $request->user() ?? new User;
        $organization = $request->route('organization');

        if (is_string($organization)) {
            $organization = UserService::getOrganizationBySlug($user, $organization);
            $this->userService->setOrganization($organization);
        }

        $this->userService->setUser($request->user() ?? new User);
    }
}
