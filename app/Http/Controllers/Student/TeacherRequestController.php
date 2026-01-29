<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreTeacherRequestRequest;
use App\Models\Location;
use App\Models\Subject;
use App\Models\TeacherRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TeacherRequestController extends Controller
{
    public function create(): View
    {
        // Check if user already has a pending request
        $existingRequest = TeacherRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return view('student.teacher-request.pending', compact('existingRequest'));
        }

        // Check if user already has an approved request (is already a teacher)
        if (auth()->user()->isTeacher()) {
            return redirect()->route('student.dashboard')
                ->with('error', __('common.You are already a teacher'));
        }

        $subjects = Subject::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();

        return view('student.teacher-request.create', compact('subjects', 'locations'));
    }

    public function store(StoreTeacherRequestRequest $request): RedirectResponse
    {
        // Check if user already has a pending request
        $existingRequest = TeacherRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            notify()->warning()
                ->title(__('common.Already Submitted'))
                ->message(__('common.You already have a pending teacher request'))
                ->send();

            return redirect()->route('student.teacher-request.create');
        }

        // Check if user is already a teacher
        if (auth()->user()->isTeacher()) {
            notify()->error()
                ->title(__('common.Error'))
                ->message(__('common.You are already a teacher'))
                ->send();

            return redirect()->route('student.dashboard');
        }

        $teacherRequest = TeacherRequest::create([
            'user_id' => auth()->id(),
            'bio' => $request->bio,
            'hourly_rate' => $request->hourly_rate,
            'qualifications' => $request->qualifications,
            'experience' => $request->experience,
            'supports_online' => $request->boolean('supports_online', false),
            'supports_in_person' => $request->boolean('supports_in_person', false),
            'default_location_id' => $request->default_location_id,
            'default_meeting_provider' => $request->default_meeting_provider ?? 'none',
            'status' => 'pending',
        ]);

        if ($request->has('subjects')) {
            $teacherRequest->subjects()->sync($request->subjects);
        }

        notify()->success()
            ->title(__('common.Submitted'))
            ->message(__('common.Your teacher request has been submitted successfully. We will review it and get back to you soon.'))
            ->send();

        return redirect()->route('student.teacher-request.create');
    }

    public function show(): View
    {
        $teacherRequest = TeacherRequest::where('user_id', auth()->id())
            ->with(['subjects', 'defaultLocation', 'reviewedBy'])
            ->latest('created_at')
            ->first();

        if (! $teacherRequest) {
            return redirect()->route('student.teacher-request.create');
        }

        return view('student.teacher-request.show', compact('teacherRequest'));
    }
}
