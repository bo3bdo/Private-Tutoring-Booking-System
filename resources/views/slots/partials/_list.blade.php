@php
    $groupedSlots = $slots->groupBy(function($slot) {
        return $slot->start_at->format('Y-m-d');
    });
@endphp

@forelse($groupedSlots as $date => $daySlots)
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
        <div class="p-6">
            <div class="mt-6 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($date)->format('D, M j, Y') }}</h3>
                <span class="text-xs text-slate-500">{{ $daySlots->count() }} {{ __('common.slots') }}</span>
            </div>

            <div class="mt-2 grid gap-2">
                @foreach($daySlots as $slot)
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold text-slate-800">
                                    {{ $slot->start_at->format('g:i A') }} – {{ $slot->end_at->format('g:i A') }}
                                </div>
                                <div class="mt-1 text-xs text-slate-500">
                                    {{ __('common.Duration') }}: {{ $slot->start_at->diffInMinutes($slot->end_at) }} {{ __('common.minutes') }}
                                </div>
                                @if(isset($isTeacher) && $isTeacher)
                                    <div class="mt-2">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold
                                            @if($slot->status->value === 'available') bg-emerald-100 text-emerald-800
                                            @elseif($slot->status->value === 'blocked') bg-rose-100 text-rose-700
                                            @else bg-slate-100 text-slate-700
                                            @endif">
                                            {{ $slot->status->label() }}
                                        </span>
                                        @if($slot->booking)
                                            <span class="ml-2 text-xs text-slate-600">{{ __('common.Student') }}: {{ $slot->booking->student->name }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            @if(isset($isTeacher) && $isTeacher)
                                <div class="flex gap-2">
                                    @if($slot->status->value === 'available')
                                        <form method="POST" action="{{ route('teacher.slots.block', $slot) }}">
                                            @csrf
                                            <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                                                {{ __('common.Block') }}
                                            </button>
                                        </form>
                                    @elseif($slot->status->value === 'blocked')
                                        <form method="POST" action="{{ route('teacher.slots.unblock', $slot) }}">
                                            @csrf
                                            <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                                {{ __('common.Unblock') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @else
                                @if($slot->status->value === 'available')
                                    <a href="{{ route('student.bookings.create', $slot) }}?subject_id={{ $subject?->id }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
                                        {{ __('common.Book') }}
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@empty
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-8 text-center">
            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8">
                <div class="text-sm font-semibold text-slate-800">{{ __('common.No available slots') }}</div>
                <div class="mt-1 text-xs text-slate-500">{{ __('common.Try changing the week or teacher.') }}</div>
            </div>
        </div>
    </div>
@endforelse
