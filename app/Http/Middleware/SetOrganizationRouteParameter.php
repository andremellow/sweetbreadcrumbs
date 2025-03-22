<?php

namespace App\Http\Middleware;

use App\Handlers\RouteParameterHandler;
use Closure;
use Illuminate\Http\Request;

class SetOrganizationRouteParameter
{
    public function __construct(protected RouteParameterHandler $routeParameterHandler) {}

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @param mixed                                                                            $request
     */
    public function handle($request, Closure $next)
    {
        if ($this->routeParameterHandler->shouldSkip()) {
            return $next($request);
        }

        $this->routeParameterHandler->handle();

        return $next($request);
    }
}