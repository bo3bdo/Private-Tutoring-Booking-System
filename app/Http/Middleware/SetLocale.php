<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from session, or fallback to config
        $locale = session('locale', config('app.locale', 'en'));

        // Validate and set locale
        if (in_array($locale, ['en', 'ar'])) {
            App::setLocale($locale);
        } else {
            // Fallback to default if invalid locale
            App::setLocale(config('app.locale', 'en'));
        }

        return $next($request);
    }
}
