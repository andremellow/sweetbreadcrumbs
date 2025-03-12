<?php

use App\Http\Middleware\SetOrganizationRouteParameter;
use App\Livewire\Meeting\ListMeetings;
use App\Livewire\Project\Dashboard as ProjectDashboard;
use App\Livewire\Project\ListProjects;
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

    Route::group(['prefix' => '/{organization:slug}', 'middleware' => ['verified']], function () {

        // Set Livewire update route (handles multi-tenant URLs)
        Livewire::setUpdateRoute(fn ($handle) => Route::post('livewire/update', $handle));

        Route::get('/projects', ListProjects::class)->name('projects.index');
        Route::get('/projects/{project}/dashboard', ProjectDashboard::class)->name('projects.dashboard');
        Route::get('/projects/{project}/meetings', ListMeetings::class)->name('projects.meetings.index');

        Route::view('dashboard', 'dashboard')->name('dashboard');

    });
    require __DIR__.'/settings.php';
});

require __DIR__.'/auth.php';
