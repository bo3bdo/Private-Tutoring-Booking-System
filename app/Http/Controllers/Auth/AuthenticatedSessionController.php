<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View|RedirectResponse
    {
        // If user is already authenticated, redirect to their dashboard
        if (Auth::check()) {
            $user = Auth::user();
            $dashboardRoute = match (true) {
                $user->isAdmin() => 'admin.dashboard',
                $user->isTeacher() => 'teacher.dashboard',
                $user->isStudent() => 'student.dashboard',
                default => null,
            };

            if ($dashboardRoute) {
                return redirect()->route($dashboardRoute);
            }
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Redirect based on user role
        $dashboardRoute = match (true) {
            $user->isAdmin() => 'admin.dashboard',
            $user->isTeacher() => 'teacher.dashboard',
            $user->isStudent() => 'student.dashboard',
            default => null,
        };

        if (! $dashboardRoute) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account does not have a valid role assigned.',
            ]);
        }

        return redirect()->route($dashboardRoute);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
