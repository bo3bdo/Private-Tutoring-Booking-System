<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\UpdateUserRoleRequest;
use App\Models\Location;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with(['roles', 'teacherProfile', 'studentProfile'])
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->load([
            'roles',
            'teacherProfile',
            'studentProfile',
            'bookings' => function ($query) {
                $query->with(['teacher.user', 'subject'])
                    ->latest('start_at')
                    ->limit(10);
            },
            'courseEnrollments' => function ($query) {
                $query->with(['course.subject'])
                    ->latest()
                    ->limit(10);
            },
        ]);

        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'completed_bookings' => $user->bookings()->where('status', 'completed')->count(),
            'total_courses' => $user->courseEnrollments()->count(),
            'total_spent' => $user->payments()->where('status', PaymentStatus::Succeeded)->sum('amount'),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function edit(User $user): View
    {
        // Prevent editing admin
        if ($user->isAdmin()) {
            abort(403, 'Cannot edit admin user');
        }

        $user->load(['roles', 'teacherProfile.subjects', 'studentProfile']);

        $subjects = Subject::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();

        $currentRole = $user->roles->first()?->name ?? 'student';
        $teacherSubjectIds = $user->teacherProfile?->subjects->pluck('id')->toArray() ?? [];

        return view('admin.users.edit', compact('user', 'subjects', 'locations', 'currentRole', 'teacherSubjectIds'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        // Prevent editing admin
        if ($user->isAdmin()) {
            notify()->error()
                ->title(__('common.Error'))
                ->message(__('common.Cannot edit admin user'))
                ->send();

            return back();
        }

        $validated = $request->validated();

        // Update user basic info
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update password if provided
        if (! empty($validated['password'])) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        // Handle role change
        $newRole = $validated['role'];
        $currentRole = $user->roles->first()?->name;

        if ($currentRole !== $newRole) {
            // Remove current role
            if ($currentRole) {
                $user->removeRole($currentRole);
            }

            // Assign new role
            $user->assignRole($newRole);

            // Handle profiles based on role
            if ($newRole === 'teacher') {
                // Delete student profile if exists
                if ($user->studentProfile) {
                    $user->studentProfile->delete();
                }

                // Create or update teacher profile
                $teacherProfile = $user->teacherProfile ?? new TeacherProfile(['user_id' => $user->id]);
                $teacherProfile->fill([
                    'bio' => $validated['bio'] ?? '',
                    'hourly_rate' => $validated['hourly_rate'] ?? 0,
                    'is_active' => $validated['is_active'] ?? true,
                    'supports_online' => $validated['supports_online'] ?? false,
                    'supports_in_person' => $validated['supports_in_person'] ?? false,
                    'default_location_id' => $validated['default_location_id'] ?? null,
                    'default_meeting_provider' => $validated['default_meeting_provider'] ?? 'none',
                ]);
                $teacherProfile->save();

                // Sync subjects
                if (isset($validated['subjects'])) {
                    $teacherProfile->subjects()->sync($validated['subjects']);
                } else {
                    $teacherProfile->subjects()->detach();
                }
            } elseif ($newRole === 'student') {
                // Delete teacher profile if exists
                if ($user->teacherProfile) {
                    $user->teacherProfile->subjects()->detach();
                    $user->teacherProfile->delete();
                }

                // Create or update student profile
                $studentProfile = $user->studentProfile ?? new StudentProfile(['user_id' => $user->id]);
                $studentProfile->fill([
                    'phone' => $validated['phone'] ?? null,
                ]);
                $studentProfile->save();
            }
        } else {
            // Role hasn't changed, just update profile
            if ($newRole === 'teacher' && $user->teacherProfile) {
                $user->teacherProfile->update([
                    'bio' => $validated['bio'] ?? $user->teacherProfile->bio,
                    'hourly_rate' => $validated['hourly_rate'] ?? $user->teacherProfile->hourly_rate,
                    'is_active' => $validated['is_active'] ?? $user->teacherProfile->is_active,
                    'supports_online' => $validated['supports_online'] ?? $user->teacherProfile->supports_online,
                    'supports_in_person' => $validated['supports_in_person'] ?? $user->teacherProfile->supports_in_person,
                    'default_location_id' => $validated['default_location_id'] ?? $user->teacherProfile->default_location_id,
                    'default_meeting_provider' => $validated['default_meeting_provider'] ?? $user->teacherProfile->default_meeting_provider,
                ]);

                // Sync subjects
                if (isset($validated['subjects'])) {
                    $user->teacherProfile->subjects()->sync($validated['subjects']);
                }
            } elseif ($newRole === 'student' && $user->studentProfile) {
                $user->studentProfile->update([
                    'phone' => $validated['phone'] ?? $user->studentProfile->phone,
                ]);
            }
        }

        notify()->success()
            ->title(__('common.Updated'))
            ->message(__('common.User updated successfully'))
            ->send();

        return redirect()->route('admin.users.index');
    }

    public function updateRole(UpdateUserRoleRequest $request, User $user): RedirectResponse
    {
        // Prevent changing admin role
        if ($user->isAdmin()) {
            notify()->error()
                ->title(__('common.Error'))
                ->message(__('common.Cannot change admin role'))
                ->send();

            return back();
        }

        $newRole = $request->validated()['role'];
        $currentRole = $user->roles->first()?->name;

        // If already has the requested role, do nothing
        if ($currentRole === $newRole) {
            notify()->info()
                ->title(__('common.Info'))
                ->message(__('common.User already has this role'))
                ->send();

            return back();
        }

        // Remove current role
        if ($currentRole) {
            $user->removeRole($currentRole);
        }

        // Assign new role
        $user->assignRole($newRole);

        // Handle profiles based on role
        if ($newRole === 'teacher') {
            // Create teacher profile if doesn't exist
            if (! $user->teacherProfile) {
                TeacherProfile::create([
                    'user_id' => $user->id,
                    'bio' => '',
                    'hourly_rate' => 0,
                    'is_active' => true,
                    'supports_online' => false,
                    'supports_in_person' => false,
                    'default_meeting_provider' => 'none',
                ]);
            }

            // Optionally remove student profile
            if ($user->studentProfile) {
                $user->studentProfile->delete();
            }
        } elseif ($newRole === 'student') {
            // Delete teacher profile if exists
            if ($user->teacherProfile) {
                $user->teacherProfile->delete();
            }

            // Create student profile if doesn't exist
            if (! $user->studentProfile) {
                StudentProfile::create([
                    'user_id' => $user->id,
                ]);
            }
        }

        notify()->success()
            ->title(__('common.Success'))
            ->message(__('common.User role updated successfully'))
            ->send();

        return back();
    }
}
