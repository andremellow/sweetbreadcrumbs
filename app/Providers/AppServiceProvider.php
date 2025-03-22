<?php

namespace App\Providers;

use App\Services\UserService;
use Illuminate\Console\Application;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // @codeCoverageIgnoreStart
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) { // @pest-mutate-ignore
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class); // @pest-mutate-ignore
            $this->app->register(TelescopeServiceProvider::class); // @pest-mutate-ignore
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('APP_ENV') !== 'production') {

            DB::listen(function (QueryExecuted $query) {
                Log::info(
                    $query->sql,
                    $query->bindings,
                    $query->time
                );
            });
        }

        $this->app->bind(UserService::class, function () {
            return new UserService(Auth::user());
        });
    }
}
