<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('student.messages.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                {{ __('common.New Message') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                <form method="POST" action="{{ route('student.messages.start') }}">
                    @csrf
                    @if($booking && $otherUser)
                        {{-- If booking is provided, show only the teacher from that booking --}}
                        <input type="hidden" name="user_id" value="{{ $otherUser->id }}">
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                        <div class="mb-6 p-4 bg-gradient-to-r from-emerald-50 to-blue-50 border-2 border-emerald-200 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center text-white font-bold shadow-md">
                                    {{ substr($otherUser->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">{{ __('common.Sending message to:') }}</p>
                                    <p class="text-lg font-bold text-emerald-700">{{ $otherUser->name }}</p>
                                    <p class="text-xs text-gray-600 mt-1">{{ __('common.Related to booking:') }} <strong>{{ $booking->subject->name }}</strong></p>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- If no booking, show all teachers --}}
                        <div class="mb-4">
                            <label for="user_id" class="block text-sm font-semibold text-gray-900 mb-2">
                                {{ __('common.Select Teacher') }}
                            </label>
                            <select name="user_id" id="user_id" required class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2 transition">
                                <option value="">{{ __('common.Choose a teacher...') }}</option>
                                @foreach(\App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'teacher'))->get() as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('student.messages.index') }}" class="px-4 py-2 border-2 border-slate-300 rounded-xl text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition">
                            {{ __('common.Cancel') }}
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-emerald-700 hover:to-emerald-800 transition">
                            {{ __('common.Start Conversation') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
