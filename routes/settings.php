<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Invites;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;
use Livewire\Volt\Volt;

Route::middleware([
    'auth',
    ValidateSessionWithWorkOS::class,
])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/invites', Invites::class)->name('settings.invites')->middleware(EnsureFeaturesAreActive::using('dev'));
    Volt::route('settings/appearance', Appearance::class)->name('settings.appearance');
});
