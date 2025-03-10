<?php

namespace App\Providers;

use App\Actions\Organization\CreateOrganization;
use App\Models\Organization;
use App\Services\OrganizationService;
use App\Services\UserService;
use Illuminate\Console\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(OrganizationService::class, function () {
            $oganization = request()->route('organization');

            return new OrganizationService(
                app(CreateOrganization::class),
                Organization::whereSlug($oganization)->first()
            );
        });

        
        $this->app->bind(UserService::class, function () {
            return new UserService(auth()->user());
        });
    }
}
