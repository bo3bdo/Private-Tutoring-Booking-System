<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateSlotsRequest;
use App\Models\TimeSlot;
use App\Services\SlotGenerationService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TimeSlotController extends Controller
{
    public function __construct(
        protected SlotGenerationService $slotService
    ) {}

    public function index(Request $request): View
    {
        $teacher = auth()->user()->teacherProfile;
        $view = $request->get('view', 'list');
        $startDate = $request->get('start') ? Carbon::parse($request->get('start')) : Carbon::now()->startOfWeek();

        $query = $teacher->timeSlots()
            ->where('start_at', '>=', $startDate)
            ->where('start_at', '<', $startDate->copy()->addWeek())
            ->with(['subject', 'booking.student']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $slots = $query->orderBy('start_at')->get();

        return view('teacher.slots.index', compact('slots', 'startDate', 'view'));
    }

    public function generate(GenerateSlotsRequest $request): RedirectResponse
    {
        $teacher = auth()->user()->teacherProfile;
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $duration = $request->duration_minutes;

        $generated = $this->slotService->generateSlots(
            teacher: $teacher,
            startDate: $startDate,
            endDate: $endDate,
            durationMinutes: $duration,
            subjectId: $request->subject_id
        );

        notify()->success()
            ->title(__('common.Created'))
            ->message(__('common.Time slots generated successfully'))
            ->send();

        return redirect()->route('teacher.slots.index');
    }

    public function block(TimeSlot $slot): RedirectResponse
    {
        $this->authorize('block', $slot);

        $slot->update(['status' => \App\Enums\SlotStatus::Blocked]);

        notify()->success()
            ->title(__('common.Blocked'))
            ->message(__('common.Time slot blocked successfully'))
            ->send();

        return back();
    }

    public function unblock(TimeSlot $slot): RedirectResponse
    {
        $this->authorize('unblock', $slot);

        $slot->update(['status' => \App\Enums\SlotStatus::Available]);

        notify()->success()
            ->title(__('common.Unblocked'))
            ->message(__('common.Time slot unblocked successfully'))
            ->send();

        return back();
    }
}
