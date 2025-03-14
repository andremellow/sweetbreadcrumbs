<?php

namespace App\Providers;

use App\Actions\Organization\CreateOrganization;
use App\Models\Organization;
use App\Models\User;
use App\Services\OrganizationService;
use App\Services\UserService;
use Illuminate\Console\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
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

            if (($organization instanceof Organization) === false) {
                $organization = Organization::whereSlug($organization)->first();
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
