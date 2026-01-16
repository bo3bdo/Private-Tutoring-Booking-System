<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(): View
    {
        $teacher = auth()->user()->teacherProfile;
        $allSubjects = Subject::where('is_active', true)->orderBy('name')->get();
        $teacherSubjects = $teacher->subjects->pluck('id')->toArray();

        return view('teacher.subjects.index', compact('allSubjects', 'teacherSubjects'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'subjects' => ['nullable', 'array'],
            'subjects.*' => ['exists:subjects,id'],
        ]);

        $teacher = auth()->user()->teacherProfile;
        $teacher->subjects()->sync($request->subjects ?? []);

        notify()->success()
            ->title('تم التحديث')
            ->message('تم تحديث المواد بنجاح')
            ->send();

        return redirect()->route('teacher.subjects.index');
    }
}
