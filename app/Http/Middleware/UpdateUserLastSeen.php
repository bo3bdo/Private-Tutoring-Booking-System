<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserLastSeen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Update last_seen_at every 30 seconds to show accurate online status
            // This ensures users appear online immediately and stay online while active
            $user = Auth::user();
            // Always update if last_seen_at is null, or if it's been more than 30 seconds
            if (! $user->last_seen_at || $user->last_seen_at->diffInSeconds(now()) >= 30) {
                $user->updateQuietly(['last_seen_at' => now()]);
                // Refresh the user model to get the updated value
                $user->refresh();
            }
        }

        return $next($request);
    }
}
