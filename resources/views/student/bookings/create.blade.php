<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Booking
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Booking Details</h3>
                        <div class="mt-2 space-y-1 text-sm text-gray-600">
                            <p><strong>Teacher:</strong> {{ $teacher->user->name }}</p>
                            <p><strong>Subject:</strong> {{ $subject->name }}</p>
                            <p><strong>Time:</strong> {{ $slot->start_at->format('l, F j, Y \a\t g:i A') }}</p>
                            <p><strong>Duration:</strong> {{ $slot->start_at->diffInMinutes($slot->end_at) }} minutes</p>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('student.bookings.store') }}">
                        @csrf
                        <input type="hidden" name="time_slot_id" value="{{ $slot->id }}">
                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lesson Mode *</label>
                            <select name="lesson_mode" id="lesson_mode" value="{{ old('lesson_mode') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 @error('lesson_mode') border-red-500 @enderror" required>
                                <option value="">Select mode</option>
                                @if($teacher->supports_online)
                                    <option value="online" {{ old('lesson_mode') === 'online' ? 'selected' : '' }}>Online</option>
                                @endif
                                @if($teacher->supports_in_person)
                                    <option value="in_person" {{ old('lesson_mode') === 'in_person' ? 'selected' : '' }}>In Person</option>
                                @endif
                            </select>
                            @error('lesson_mode')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="online_fields" class="mb-4 hidden">
                            <div class="rounded-lg bg-blue-50 border border-blue-200 p-3">
                                <p class="text-sm text-blue-800">
                                    <strong>Note:</strong> The meeting URL will be provided by your teacher after booking confirmation.
                                </p>
                            </div>
                        </div>

                        <div id="in_person_fields" class="mb-4 hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                            <select name="location_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 @error('location_id') border-red-500 @enderror">
                                <option value="">Select location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                @endforeach
                            </select>
                            @error('location_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                            <textarea name="notes" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2" placeholder="Any special requests or notes..."></textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ url()->previous() }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Cancel
                            </a>
                            <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
                                Confirm Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('lesson_mode').addEventListener('change', function() {
            const onlineFields = document.getElementById('online_fields');
            const inPersonFields = document.getElementById('in_person_fields');
            
            onlineFields.classList.add('hidden');
            inPersonFields.classList.add('hidden');
            
            if (this.value === 'online') {
                onlineFields.classList.remove('hidden');
            } else if (this.value === 'in_person') {
                inPersonFields.classList.remove('hidden');
            }
        });
    </script>
</x-app-layout>
