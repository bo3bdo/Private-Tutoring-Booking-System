<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherProfile;
use App\Models\TeacherRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherRequestController extends Controller
{
    public function index(Request $request): View
    {
        $query = TeacherRequest::with(['user', 'subjects', 'defaultLocation', 'reviewedBy']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('qualifications', 'like', "%{$search}%")
                    ->orWhere('experience', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $requests = $query->latest('created_at')->paginate(20);

        // Statistics
        $pendingCount = TeacherRequest::where('status', 'pending')->count();
        $approvedCount = TeacherRequest::where('status', 'approved')->count();
        $rejectedCount = TeacherRequest::where('status', 'rejected')->count();

        return view('admin.teacher-requests.index', compact('requests', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    public function show(TeacherRequest $teacherRequest): View
    {
        $teacherRequest->load(['user', 'subjects', 'defaultLocation', 'reviewedBy']);

        return view('admin.teacher-requests.show', compact('teacherRequest'));
    }

    public function approve(Request $request, TeacherRequest $teacherRequest): RedirectResponse
    {
        // Check if request is already processed
        if (! $teacherRequest->isPending()) {
            notify()->warning()
                ->title(__('common.Already Processed'))
                ->message(__('common.This request has already been processed'))
                ->send();

            return back();
        }

        // Check if user is already a teacher
        if ($teacherRequest->user->isTeacher()) {
            notify()->error()
                ->title(__('common.Error'))
                ->message(__('common.User is already a teacher'))
                ->send();

            return back();
        }

        // Create teacher profile
        $teacherProfile = TeacherProfile::create([
            'user_id' => $teacherRequest->user_id,
            'bio' => $teacherRequest->bio,
            'hourly_rate' => $teacherRequest->hourly_rate ?? 25.00,
            'is_active' => true,
            'supports_online' => $teacherRequest->supports_online,
            'supports_in_person' => $teacherRequest->supports_in_person,
            'default_location_id' => $teacherRequest->default_location_id,
            'default_meeting_provider' => $teacherRequest->default_meeting_provider,
        ]);

        // Sync subjects
        if ($teacherRequest->subjects->isNotEmpty()) {
            $teacherProfile->subjects()->sync($teacherRequest->subjects->pluck('id'));
        }

        // Assign teacher role
        $teacherRequest->user->assignRole('teacher');

        // Update request status
        $teacherRequest->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        notify()->success()
            ->title(__('common.Approved'))
            ->message(__('common.Teacher request approved successfully. User has been assigned teacher role.'))
            ->send();

        return redirect()->route('admin.teacher-requests.index');
    }

    public function reject(Request $request, TeacherRequest $teacherRequest): RedirectResponse
    {
        // Check if request is already processed
        if (! $teacherRequest->isPending()) {
            notify()->warning()
                ->title(__('common.Already Processed'))
                ->message(__('common.This request has already been processed'))
                ->send();

            return back();
        }

        $request->validate([
            'admin_notes' => ['required', 'string', 'max:1000'],
        ]);

        // Update request status
        $teacherRequest->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        notify()->success()
            ->title(__('common.Rejected'))
            ->message(__('common.Teacher request rejected successfully'))
            ->send();

        return redirect()->route('admin.teacher-requests.index');
    }
}
