<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('student.messages.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                New Message
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                <form method="POST" action="{{ route('student.messages.start') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="user_id" class="block text-sm font-semibold text-gray-900 mb-2">
                            Select Teacher
                        </label>
                        <select name="user_id" id="user_id" required class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2 transition">
                            <option value="">Choose a teacher...</option>
                            @foreach(\App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'teacher'))->get() as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($booking)
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                            <p class="text-sm text-blue-900">This conversation is related to booking: <strong>{{ $booking->subject->name }}</strong></p>
                        </div>
                    @endif
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('student.messages.index') }}" class="px-4 py-2 border-2 border-slate-300 rounded-xl text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-emerald-700 hover:to-emerald-800 transition">
                            Start Conversation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
