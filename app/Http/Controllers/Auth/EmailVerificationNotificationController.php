<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            $dashboardRoute = match (true) {
                $user->isAdmin() => 'admin.dashboard',
                $user->isTeacher() => 'teacher.dashboard',
                $user->isStudent() => 'student.dashboard',
                default => 'login',
            };

            return redirect()->intended(route($dashboardRoute, absolute: false));
        }

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
