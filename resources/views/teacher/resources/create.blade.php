<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('teacher.resources.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                Upload Resource
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                <form method="POST" action="{{ route('teacher.resources.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if($resourceable)
                        <input type="hidden" name="resourceable_type" value="{{ get_class($resourceable) }}">
                        <input type="hidden" name="resourceable_id" value="{{ $resourceable->id }}">
                        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                            <p class="text-sm text-blue-900">Uploading resource for: <strong>{{ class_basename($resourceable) }}</strong></p>
                        </div>
                    @else
                        <div class="mb-4">
                            <label for="resourceable_type" class="block text-sm font-semibold text-gray-900 mb-2">
                                Resource Type <span class="text-red-500">*</span>
                            </label>
                            <select name="resourceable_type" id="resourceable_type" required class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition">
                                <option value="">Select type...</option>
                                <option value="App\Models\Course">Course</option>
                                <option value="App\Models\Booking">Booking</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="resourceable_id" class="block text-sm font-semibold text-gray-900 mb-2">
                                Select Item <span class="text-red-500">*</span>
                            </label>
                            <select name="resourceable_id" id="resourceable_id" required class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition">
                                <option value="">Select item...</option>
                            </select>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" required value="{{ old('title') }}" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition @error('title') border-red-500 @enderror" placeholder="Resource title">
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition resize-none" placeholder="Brief description of the resource...">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="file" class="block text-sm font-semibold text-gray-900 mb-2">
                            File <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="file" id="file" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip,.rar" class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-500 mt-1">Max 50MB</p>
                        @error('file')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Make this resource public to all students</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('teacher.resources.index') }}" class="px-4 py-2 border-2 border-slate-300 rounded-xl text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-blue-700 hover:to-blue-800 transition">
                            Upload Resource
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const bookings = @json($bookings ?? []);
        const courses = @json($courses ?? []);
        const resourceableType = document.getElementById('resourceable_type');
        const resourceableId = document.getElementById('resourceable_id');

        if (resourceableType && resourceableId) {
            resourceableType.addEventListener('change', function() {
                resourceableId.innerHTML = '<option value="">Select item...</option>';
                
                if (this.value === 'App\\Models\\Booking') {
                    if (bookings.length === 0) {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'No bookings available';
                        option.disabled = true;
                        resourceableId.appendChild(option);
                    } else {
                        bookings.forEach(booking => {
                            const option = document.createElement('option');
                            option.value = booking.id;
                            option.textContent = `${booking.subject_name} - ${booking.student_name} (${booking.formatted_date})`;
                            resourceableId.appendChild(option);
                        });
                    }
                } else if (this.value === 'App\\Models\\Course') {
                    if (courses.length === 0) {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'No courses available';
                        option.disabled = true;
                        resourceableId.appendChild(option);
                    } else {
                        courses.forEach(course => {
                            const option = document.createElement('option');
                            option.value = course.id;
                            option.textContent = course.title;
                            resourceableId.appendChild(option);
                        });
                    }
                }
            });
        }
    </script>
</x-app-layout>
