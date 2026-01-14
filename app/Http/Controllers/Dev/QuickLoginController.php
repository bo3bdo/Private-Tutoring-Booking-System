<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class QuickLoginController extends Controller
{
    public function admin(): RedirectResponse
    {
        if (! app()->environment('local') && ! config('app.debug')) {
            abort(404);
        }

        $user = User::where('email', 'admin@example.com')->first();

        if (! $user) {
            abort(404, 'Admin user not found. Please run seeders.');
        }

        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }

    public function teacher(): RedirectResponse
    {
        if (! app()->environment('local') && ! config('app.debug')) {
            abort(404);
        }

        $user = User::where('email', 'teacher@example.com')->first();

        if (! $user) {
            abort(404, 'Teacher user not found. Please run seeders.');
        }

        Auth::login($user);

        return redirect()->route('teacher.dashboard');
    }

    public function student(): RedirectResponse
    {
        if (! app()->environment('local') && ! config('app.debug')) {
            abort(404);
        }

        $user = User::where('email', 'student@example.com')->first();

        if (! $user) {
            abort(404, 'Student user not found. Please run seeders.');
        }

        Auth::login($user);

        return redirect()->route('student.subjects.index');
    }
}
