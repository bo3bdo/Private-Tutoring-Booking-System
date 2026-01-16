<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTeacherRequest;
use App\Http\Requests\Admin\UpdateTeacherRequest;
use App\Models\Location;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(): View
    {
        $teachers = TeacherProfile::with(['user', 'subjects', 'defaultLocation'])
            ->latest()
            ->paginate(15);

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create(): View
    {
        $subjects = Subject::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();

        return view('admin.teachers.create', compact('subjects', 'locations'));
    }

    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('teacher');

        $teacherProfile = TeacherProfile::create([
            'user_id' => $user->id,
            'bio' => $request->bio,
            'hourly_rate' => $request->hourly_rate,
            'is_active' => $request->boolean('is_active', true),
            'supports_online' => $request->boolean('supports_online', false),
            'supports_in_person' => $request->boolean('supports_in_person', false),
            'default_location_id' => $request->default_location_id,
            'default_meeting_provider' => $request->default_meeting_provider ?? 'none',
        ]);

        if ($request->has('subjects')) {
            $teacherProfile->subjects()->sync($request->subjects);
        }

        notify()->success()
            ->title(__('common.Created'))
            ->message(__('common.Teacher created successfully'))
            ->send();

        return redirect()->route('admin.teachers.index');
    }

    public function show(TeacherProfile $teacher): View
    {
        $teacher->load(['user', 'subjects', 'defaultLocation', 'bookings.student', 'bookings.subject']);

        return view('admin.teachers.show', compact('teacher'));
    }

    public function edit(TeacherProfile $teacher): View
    {
        $teacher->load(['user', 'subjects']);
        $subjects = Subject::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();
        $teacherSubjectIds = $teacher->subjects->pluck('id')->toArray();

        return view('admin.teachers.edit', compact('teacher', 'subjects', 'locations', 'teacherSubjectIds'));
    }

    public function update(UpdateTeacherRequest $request, TeacherProfile $teacher): RedirectResponse
    {
        $user = $teacher->user;

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $teacher->update([
            'bio' => $request->bio,
            'hourly_rate' => $request->hourly_rate,
            'is_active' => $request->boolean('is_active', $teacher->is_active),
            'supports_online' => $request->boolean('supports_online', $teacher->supports_online),
            'supports_in_person' => $request->boolean('supports_in_person', $teacher->supports_in_person),
            'default_location_id' => $request->default_location_id,
            'default_meeting_provider' => $request->default_meeting_provider ?? $teacher->default_meeting_provider,
        ]);

        if ($request->has('subjects')) {
            $teacher->subjects()->sync($request->subjects);
        } else {
            $teacher->subjects()->detach();
        }

        notify()->success()
            ->title(__('common.Updated'))
            ->message(__('common.Teacher updated successfully'))
            ->send();

        return redirect()->route('admin.teachers.index');
    }

    public function destroy(TeacherProfile $teacher): RedirectResponse
    {
        if ($teacher->bookings()->exists()) {
            notify()->error()
                ->title(__('common.Error'))
                ->message(__('common.Cannot delete teacher with bookings'))
                ->send();

            return back();
        }

        $user = $teacher->user;
        $teacher->delete();
        $user->delete();

        notify()->success()
            ->title(__('common.Deleted'))
            ->message(__('common.Teacher deleted successfully'))
            ->send();

        return redirect()->route('admin.teachers.index');
    }
}
