<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class QuickLoginController extends Controller
{
    public function admin(): RedirectResponse
    {
        $user = User::where('email', 'admin@example.com')->first();

        if (! $user) {
            Log::warning('Quick login attempted but admin user not found');
            abort(404, 'Admin user not found. Please run: php artisan db:seed');
        }

        Auth::login($user);

        notify()->success()
            ->title(__('common.Logged in'))
            ->message(__('common.Logged in as admin'))
            ->send();

        return redirect()->route('admin.dashboard');
    }

    public function teacher(): RedirectResponse
    {
        $user = User::where('email', 'teacher@example.com')->first();

        if (! $user) {
            Log::warning('Quick login attempted but teacher user not found');
            abort(404, 'Teacher user not found. Please run: php artisan db:seed');
        }

        Auth::login($user);

        notify()->success()
            ->title(__('common.Logged in'))
            ->message(__('common.Logged in as teacher'))
            ->send();

        return redirect()->route('teacher.dashboard');
    }

    public function student(): RedirectResponse
    {
        $user = User::where('email', 'student@example.com')->first();

        if (! $user) {
            Log::warning('Quick login attempted but student user not found');
            abort(404, 'Student user not found. Please run: php artisan db:seed');
        }

        Auth::login($user);

        notify()->success()
            ->title(__('common.Logged in'))
            ->message(__('common.Logged in as student'))
            ->send();

        return redirect()->route('student.dashboard');
    }
}
