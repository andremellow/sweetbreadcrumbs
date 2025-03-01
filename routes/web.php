<?php

use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectDashboardController;
use App\Http\Controllers\WelcomeOrganizationController;
use App\Models\Organization;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('old.dashboard');

    Route::group(['prefix' => '/{organization:slug}', 'middleware' => ['verified']], function () {
        Route::get('/dashboard', function (Organization $organization) {
            return Inertia::render('dashboard');
        })->name('dashboard');

        Route::get('/projects', [ProjectController::class, 'index'])->name('projects');
        Route::get('/projects/{project}/dashboard', [ProjectDashboardController::class, 'index'])->name('projects.dashboard');
        Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects/create', [ProjectController::class, 'store'])->name('projects.store');
        Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::patch('/projects/{project}/edit', [ProjectController::class, 'update'])->name('projects.update');

        // MEETINGS
        Route::get('/projects/{project}/meetings', [MeetingController::class, 'index'])->name('projects.meetings');
        Route::get('/projects/{project}/meetings/{meeting}', [MeetingController::class, 'edit'])->name('projects.meetings.edit');
        Route::post('/projects/{project}/meetings/create', [MeetingController::class, 'store'])->name('projects.meetings.store');
        Route::patch('/projects/{project}/meetings/{meeting}/edit', [MeetingController::class, 'update'])->name('projects.meetings.update');
    });

    Route::get('/welcome/organization', [WelcomeOrganizationController::class, 'create'])->name('welcome.organization');
    Route::post('/welcome/organization', [WelcomeOrganizationController::class, 'store'])->name('welcome.organization.store');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
