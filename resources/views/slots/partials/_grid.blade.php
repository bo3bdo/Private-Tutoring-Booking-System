<div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
    <table class="min-w-[900px] w-full table-fixed border-separate border-spacing-0">
        <thead class="sticky top-0 bg-white z-10">
            <tr>
                <th class="sticky left-0 z-20 bg-white px-3 py-2 text-left text-xs font-semibold text-slate-600 border-b border-slate-200">
                    {{ __('common.Time') }}
                </th>
                @for($i = 0; $i < 7; $i++)
                    @php
                        $date = ($startDate ?? now()->startOfWeek())->copy()->addDays($i);
                    @endphp
                    <th class="px-3 py-2 text-center text-xs font-semibold text-slate-700 border-b border-slate-200">
                        <div>{{ $date->format('D') }}</div>
                        <div class="text-[10px] text-slate-500 mt-0.5">{{ $date->format('M j') }}</div>
                    </th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @php
                $timeSlots = [];
                $startDate = $startDate ?? $slots->first()?->start_at?->copy()->startOfWeek() ?? now()->startOfWeek();
                foreach ($slots as $slot) {
                    $day = $slot->start_at->diffInDays($startDate);
                    if ($day >= 0 && $day < 7) {
                        $time = $slot->start_at->format('H:i');
                        if (!isset($timeSlots[$time])) {
                            $timeSlots[$time] = array_fill(0, 7, null);
                        }
                        $timeSlots[$time][$day] = $slot;
                    }
                }
                ksort($timeSlots);
            @endphp

            @foreach($timeSlots as $time => $daySlots)
                <tr>
                    <td class="sticky left-0 z-20 bg-white px-3 py-2 text-xs font-semibold text-slate-600 border-b border-slate-100 border-r border-slate-100">
                        {{ $time }}
                    </td>
                    @foreach($daySlots as $day => $slot)
                        <td class="h-16 align-top px-2 py-2 border-b border-slate-100 border-r border-slate-100 {{ $slot ? '' : 'bg-slate-50/30' }}">
                            @if($slot)
                                @if(isset($isTeacher) && $isTeacher)
                                    @if($slot->status->value === 'available')
                                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-2 py-2">
                                            <div class="text-[11px] font-semibold text-emerald-800">Available</div>
                                            <div class="mt-1 text-[11px] text-slate-600">{{ $slot->start_at->format('H:i') }}–{{ $slot->end_at->format('H:i') }}</div>
                                            <form method="POST" action="{{ route('teacher.slots.block', $slot) }}" class="mt-2">
                                                @csrf
                                                <button type="submit" class="w-full rounded-lg bg-rose-600 px-2 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">
                                                    {{ __('common.Block') }}
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($slot->status->value === 'blocked')
                                        <div class="rounded-xl border border-rose-200 bg-rose-50 px-2 py-2">
                                            <span class="inline-flex items-center rounded-full bg-rose-100 px-2 py-0.5 text-[11px] font-semibold text-rose-700">{{ __('common.Blocked') }}</span>
                                            <form method="POST" action="{{ route('teacher.slots.unblock', $slot) }}" class="mt-2">
                                                @csrf
                                                <button type="submit" class="w-full rounded-lg bg-emerald-600 px-2 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">
                                                    {{ __('common.Unblock') }}
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($slot->status->value === 'booked')
                                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-2 py-2">
                                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-700">{{ __('common.Booked') }}</span>
                                            @if($slot->booking)
                                                <div class="mt-1 text-[10px] text-slate-600">{{ __('common.Student') }}: {{ $slot->booking->student->name }}</div>
                                            @endif
                                        </div>
                                    @endif
                                @else
                                    @if($slot->status->value === 'available')
                                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-2 py-2">
                                            <div class="text-[11px] font-semibold text-emerald-800">{{ __('common.Available') }}</div>
                                            <div class="mt-1 text-[11px] text-slate-600">{{ $slot->start_at->format('H:i') }}–{{ $slot->end_at->format('H:i') }}</div>
                                            <a href="{{ route('student.bookings.create', $slot) }}?subject_id={{ $subject?->id }}" class="mt-2 block w-full rounded-lg bg-emerald-600 px-2 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2 text-center">
                                                {{ __('common.Book') }}
                                            </a>
                                        </div>
                                    @endif
                                @endif
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($slots->isEmpty())
        <div class="p-8 text-center">
            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8">
                <div class="text-sm font-semibold text-slate-800">{{ __('common.No available slots') }}</div>
                <div class="mt-1 text-xs text-slate-500">{{ __('common.Try changing the week or teacher.') }}</div>
            </div>
        </div>
    @endif
</div>
