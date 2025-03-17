<?php

use App\Http\Middleware\SetOrganizationRouteParameter;
use App\Livewire\Meeting\ListMeetings;
use App\Livewire\Workstream\Dashboard as WorkstreamDashboard;
use App\Livewire\Organization\Dashboard as OrganizationDashboard;
use App\Livewire\Organization\Welcome;
use App\Livewire\Workstream\ListWorkstreams;
use App\Livewire\Task\ListTasks;
use App\Livewire\Welcome\Organization as WelcomeOrganization;
use App\Livewire\Welcome\Profile as WelcomeProfile;
use App\Livewire\Welcome\Workstream as WelcomeWorkstream;
use Illuminate\Support\Facades\Route;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;
use Livewire\Livewire;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth',
    ValidateSessionWithWorkOS::class,
    SetOrganizationRouteParameter::class,
])->group(function () {


    // Set Livewire update route (handles multi-tenant URLs)
    Livewire::setUpdateRoute(fn ($handle) => Route::post('livewire/update', $handle));

    Route::group(['middleware' => ['verified']], function () {
        Route::get('welcome/profile', WelcomeProfile::class)->name('welcome.profile')->withoutMiddleware(SetOrganizationRouteParameter::class);
        Route::get('welcome/organization', WelcomeOrganization::class)->name('welcome.organization')->withoutMiddleware(SetOrganizationRouteParameter::class);
        require __DIR__.'/settings.php';
    });

    Route::group(['prefix' => '/{organization:slug}', 'middleware' => ['verified']], function () {
        Route::get('welcome/workstream', WelcomeWorkstream::class)->name('welcome.workstream');

        Route::get('/workstreams', ListWorkstreams::class)->name('workstreams.index');
        Route::get('/workstreams/{workstream}/dashboard', WorkstreamDashboard::class)->name('workstreams.dashboard');
        Route::get('/workstreams/{workstream}/meetings', ListMeetings::class)->name('workstreams.meetings.index');
        Route::get('/workstreams/{workstream}/tasks', ListTasks::class)->name('workstreams.tasks.index');

        Route::get('dashboard', OrganizationDashboard::class)->name('dashboard');
    });
    
});

require __DIR__.'/auth.php';
