<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $userService = new UserService($request->user());
        $organization = $userService->getCurrentOrganization();
        

        if ($request->user()->hasVerifiedEmail()) {
            if ($organization === null) {
                return redirect(route('welcome.organization'));
            }
    
            return redirect()->intended(route('dashboard', ['organization' => $organization->slug, 'verified' => 1], absolute: false));
        }

        if ($request->user()->markEmailAsVerified()) {
            /** @var \Illuminate\Contracts\Auth\MustVerifyEmail $user */
            $user = $request->user();

            event(new Verified($user));
        }

        if ($organization === null) {
            return redirect(route('welcome.organization'));
        }

        return redirect()->intended(route('dashboard', ['organization' => $organization->slug, 'verified' => 1], absolute: false));
    }
}
