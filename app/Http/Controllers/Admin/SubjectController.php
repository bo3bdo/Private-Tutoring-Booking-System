<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubjectRequest;
use App\Http\Requests\Admin\UpdateSubjectRequest;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(): View
    {
        $subjects = Subject::latest('created_at')->paginate(15);

        return view('admin.subjects.index', compact('subjects'));
    }

    public function create(): View
    {
        return view('admin.subjects.create');
    }

    public function store(StoreSubjectRequest $request): RedirectResponse
    {
        Subject::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
        ]);

        notify()->success()
            ->title(__('common.Created'))
            ->message(__('common.Subject created successfully'))
            ->send();

        return redirect()->route('admin.subjects.index');
    }

    public function show(Subject $subject): View
    {
        $subject->load(['teachers.user', 'bookings']);

        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject): View
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(UpdateSubjectRequest $request, Subject $subject): RedirectResponse
    {
        $subject->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', $subject->is_active),
        ]);

        notify()->success()
            ->title(__('common.Updated'))
            ->message(__('common.Subject updated successfully'))
            ->send();

        return redirect()->route('admin.subjects.index');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        if ($subject->bookings()->exists()) {
            notify()->error()
                ->title(__('common.Error'))
                ->message(__('common.Cannot delete subject with bookings'))
                ->send();

            return back();
        }

        $subject->delete();

        notify()->success()
            ->title(__('common.Deleted'))
            ->message(__('common.Subject deleted successfully'))
            ->send();

        return redirect()->route('admin.subjects.index');
    }
}
