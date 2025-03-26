<?php

use App\Services\UserService;
use Illuminate\Support\Facades\Route;
use Laravel\WorkOS\Http\Requests\AuthKitAuthenticationRequest;
use Laravel\WorkOS\Http\Requests\AuthKitLoginRequest;
use Laravel\WorkOS\Http\Requests\AuthKitLogoutRequest;

Route::get('login', function (AuthKitLoginRequest $request) {
    return $request->redirect();
})->middleware(['guest'])->name('login');

Route::get('authenticate', function (AuthKitAuthenticationRequest $request) {
    $request->authenticate();
    $user = $request->user();
    $userService = new UserService($user);
    $organization = $userService->getCurrentOrganization();

    if ($user->first_name === null || $user->first_name === '') {
        return redirect(route('welcome.profile'));
    } elseif ($organization === null) {
        return redirect()->intended(route('welcome.organization'));
    }

    // SE NÃO TEM NOME, VAI POR PROFILE......

    return redirect()->intended(route('dashboard', ['organization' => $organization->slug], absolute: false));

})->middleware(['guest']);

Route::post('logout', function (AuthKitLogoutRequest $request) {
    return $request->logout();
})->middleware(['auth'])->name('logout');
