<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DevOnlyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow in local environment OR when debug is enabled
        $isLocal = app()->environment('local');
        $isDebug = filter_var(config('app.debug', false), FILTER_VALIDATE_BOOLEAN);

        if ($isLocal || $isDebug) {
            return $next($request);
        }

        abort(404, 'Dev routes are only available in local environment or when debug is enabled.');
    }
}
