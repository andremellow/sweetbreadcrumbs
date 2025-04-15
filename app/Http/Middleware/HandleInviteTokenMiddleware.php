<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HandleInviteTokenMiddleware
{
    public function handle(Request $request, Closure $next): RedirectResponse
    {
        $token = $request->route('invite');

        if ($token) {
            $request->session()->put('invite', $token);
        }

        return $next($request);
    }
}
