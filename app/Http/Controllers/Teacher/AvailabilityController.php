<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TeacherAvailability;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AvailabilityController extends Controller
{
    public function index(): View
    {
        $teacher = auth()->user()->teacherProfile;
        $availabilities = $teacher->availabilities()->orderBy('weekday')->orderBy('start_time')->get();

        return view('teacher.availability.index', compact('availabilities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $teacher = auth()->user()->teacherProfile;

        $request->validate([
            'weekday' => ['required', 'integer', 'min:0', 'max:6'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        TeacherAvailability::create([
            'teacher_id' => $teacher->id,
            'weekday' => $request->weekday,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_active' => true,
        ]);

        return redirect()->route('teacher.availability.index')
            ->with('success', 'Availability added successfully.');
    }

    public function destroy(TeacherAvailability $availability): RedirectResponse
    {
        $this->authorize('delete', $availability);

        $availability->delete();

        return redirect()->route('teacher.availability.index')
            ->with('success', 'Availability removed successfully.');
    }
}
