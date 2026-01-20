<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\TeacherProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(): View
    {
        $subjects = Subject::where('is_active', true)->latest()->get();

        return view('student.subjects.index', compact('subjects'));
    }

    public function show(Subject $subject): View
    {
        // Ensure subject is active
        if (! $subject->is_active) {
            abort(404, 'Subject not found or inactive');
        }

        $teachers = $subject->teachers()
            ->where('is_active', true)
            ->with(['user', 'reviews' => function ($query) {
                $query->where('is_approved', true);
            }])
            ->latest()
            ->get();

        return view('student.subjects.show', compact('subject', 'teachers'));
    }

    public function slots(TeacherProfile $teacher, Request $request): View
    {
        $subjectId = $request->get('subject_id');
        $view = $request->get('view', 'list');
        $startDate = $request->get('start') ? Carbon::parse($request->get('start')) : Carbon::now()->startOfWeek();

        // Ensure startDate is not in the past
        if ($startDate->isPast()) {
            $startDate = Carbon::now()->startOfWeek();
        }

        $query = $teacher->timeSlots()
            ->where('start_at', '>=', $startDate)
            ->where('start_at', '<', $startDate->copy()->addWeek())
            ->where('end_at', '>', now()) // Only show slots that haven't ended yet
            ->where('status', 'available');

        if ($subjectId) {
            $query->where(function ($q) use ($subjectId) {
                $q->where('subject_id', $subjectId)
                    ->orWhereNull('subject_id');
            });
        }

        $slots = $query->orderBy('start_at')->get();

        $subject = $subjectId ? Subject::findOrFail($subjectId) : null;

        return view('student.slots.index', compact('slots', 'teacher', 'subject', 'startDate', 'view'));
    }
}
