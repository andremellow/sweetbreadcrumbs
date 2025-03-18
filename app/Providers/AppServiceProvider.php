<?php

namespace App\Providers;

use App\Actions\Organization\CreateOrganization;
use App\Models\Organization;
use App\Services\OrganizationService;
use App\Services\UserService;
use Illuminate\Console\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // @codeCoverageIgnoreStart
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('APP_ENV') !== 'production') {
            DB::listen(function ($query) {
                Log::info(
                    $query->sql,
                    $query->bindings,
                    $query->time
                );
            });
        }

        $this->app->bind(OrganizationService::class, function () {
            $organization = request()->route('organization');
            // If there is no biding on the component mount
            // Organization is just a string, need to
            // Get Organization from DB
            if (($organization instanceof Organization) === false) {
                $organization = Organization::whereSlug($organization)->first();
            }
            if (request()->hasSession()) {
                if ($organization) {
                    // When organiation is present, need to check if session need to be updated
                    if ($organization->id !== intval(request()->session()->get('current_organization_id'))) {
                        request()->session()->put('current_organization_id', $organization->id);
                    }
                } elseif (request()->session()->has('current_organization_id')) {
                    // If organization is not present, need to get it from the session
                    $organization = Organization::find(request()->session()->get('current_organization_id'));
                }
            }

            return new OrganizationService(
                app(CreateOrganization::class),
                $organization
            );
        });

        $this->app->bind(UserService::class, function () {
            return new UserService(auth()->user());
        });
    }
}
