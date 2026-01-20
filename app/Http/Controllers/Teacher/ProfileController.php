<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\MeetingProvider;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\UpdateProfileRequest;
use App\Models\Location;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $teacher = auth()->user()->teacherProfile;
        $subjects = Subject::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();
        $teacherSubjectIds = $teacher->subjects->pluck('id')->toArray();
        $meetingProviders = MeetingProvider::cases();

        return view('teacher.profile.edit', compact('teacher', 'subjects', 'locations', 'teacherSubjectIds', 'meetingProviders'));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $teacher = auth()->user()->teacherProfile;

        $teacher->update([
            'bio' => $request->bio,
            'hourly_rate' => $request->hourly_rate,
            'supports_online' => $request->boolean('supports_online', $teacher->supports_online),
            'supports_in_person' => $request->boolean('supports_in_person', $teacher->supports_in_person),
            'default_location_id' => $request->default_location_id,
            'default_meeting_provider' => $request->default_meeting_provider ?? $teacher->default_meeting_provider,
        ]);

        $teacher->subjects()->sync($request->subjects);

        notify()->success()
            ->title(__('common.Updated'))
            ->message(__('common.Profile updated successfully'))
            ->send();

        return redirect()->route('teacher.profile.edit');
    }
}
