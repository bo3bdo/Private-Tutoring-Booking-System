<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Availability') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Availability</h3>
                    <form method="POST" action="{{ route('teacher.availability.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Day</label>
                            <select name="weekday" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                                <option value="0">Sunday</option>
                                <option value="1">Monday</option>
                                <option value="2">Tuesday</option>
                                <option value="3">Wednesday</option>
                                <option value="4">Thursday</option>
                                <option value="5">Friday</option>
                                <option value="6">Saturday</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                            <input type="time" name="start_time" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                            <input type="time" name="end_time" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                                Add
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Availability</h3>
                    <div class="space-y-3">
                        @forelse($availabilities as $availability)
                            <div class="flex items-center justify-between p-4 border border-slate-200 rounded-lg">
                                <div>
                                    <p class="font-semibold">{{ ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][$availability->weekday] }}</p>
                                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($availability->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($availability->end_time)->format('g:i A') }}</p>
                                </div>
                                <form method="POST" action="{{ route('teacher.availability.destroy', $availability) }}" onsubmit="return confirm('Remove this availability?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-8">No availability set. Add your available times above.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
