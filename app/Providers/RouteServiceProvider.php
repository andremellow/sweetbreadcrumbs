<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // parent::boot();

        Route::macro('withOrg', function ($name, $parameters = [], $absolute = true) {
            // Retrieve the organization from the current request
            $organization = request()->route('organization');

            // If organization exists, add it to the route parameters
            if ($organization) {
                $parameters = array_merge(['organization' => $organization], $parameters);
            }

            return route($name, $parameters, $absolute);
        });
    }
}
