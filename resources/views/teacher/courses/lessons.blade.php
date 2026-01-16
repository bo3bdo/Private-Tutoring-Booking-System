<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('teacher.courses.show', $course) }}" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manage Lessons: {{ $course->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Add Lesson Form -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Add New Lesson</h3>
                <form method="POST" action="{{ route('teacher.lessons.store', $course) }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">Title *</label>
                            <input type="text" name="title" id="title" required
                                class="w-full rounded-xl border-2 border-slate-200 px-4 py-2 text-sm text-gray-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition">
                        </div>
                        <div>
                            <label for="sort_order" class="block text-sm font-semibold text-gray-900 mb-2">Order</label>
                            <input type="number" name="sort_order" id="sort_order" min="0"
                                class="w-full rounded-xl border-2 border-slate-200 px-4 py-2 text-sm text-gray-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition">
                        </div>
                    </div>
                    <div>
                        <label for="summary" class="block text-sm font-semibold text-gray-900 mb-2">Summary</label>
                        <textarea name="summary" id="summary" rows="2"
                            class="w-full rounded-xl border-2 border-slate-200 px-4 py-2 text-sm text-gray-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition resize-none"></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="video_provider" class="block text-sm font-semibold text-gray-900 mb-2">Provider *</label>
                            <select name="video_provider" id="video_provider" required
                                class="w-full rounded-xl border-2 border-slate-200 px-4 py-2 text-sm text-gray-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition">
                                <option value="url">Direct URL</option>
                                <option value="youtube">YouTube</option>
                                <option value="vimeo">Vimeo</option>
                                <option value="s3">AWS S3</option>
                                <option value="cloudflare">Cloudflare</option>
                            </select>
                        </div>
                        <div>
                            <label for="video_url" class="block text-sm font-semibold text-gray-900 mb-2">Video URL *</label>
                            <input type="text" name="video_url" id="video_url" required
                                class="w-full rounded-xl border-2 border-slate-200 px-4 py-2 text-sm text-gray-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition">
                        </div>
                        <div>
                            <label for="duration_seconds" class="block text-sm font-semibold text-gray-900 mb-2">Duration (seconds)</label>
                            <input type="number" name="duration_seconds" id="duration_seconds" min="0"
                                class="w-full rounded-xl border-2 border-slate-200 px-4 py-2 text-sm text-gray-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition">
                        </div>
                    </div>
                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_free_preview" value="1"
                                class="w-4 h-4 rounded border-slate-300 text-slate-600 focus:ring-slate-500">
                            <span class="ml-2 text-sm text-gray-700 font-medium">Free Preview</span>
                        </label>
                    </div>
                    <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-slate-900 to-slate-800 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-slate-800 hover:to-slate-700 transition">
                        Add Lesson
                    </button>
                </form>
            </div>

            <!-- Lessons List -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Lessons ({{ $lessons->count() }})</h3>
                    <div class="space-y-3">
                        @forelse($lessons as $lesson)
                            <div class="flex items-center gap-4 p-4 border border-slate-200 rounded-xl">
                                <div class="flex-shrink-0 w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center font-semibold text-gray-700">
                                    {{ $lesson->sort_order }}
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $lesson->title }}</h4>
                                    <p class="text-sm text-gray-600">{{ $lesson->video_provider->label() }} • {{ $lesson->durationFormatted() }}</p>
                                    @if($lesson->is_free_preview)
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-800 mt-1">Preview</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="openEditModal({{ $lesson->id }})" class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold">Edit</button>
                                    <form method="POST" action="{{ route('teacher.lessons.destroy', $lesson) }}" onsubmit="return confirm('Delete this lesson?')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-semibold">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-8">No lessons yet. Add your first lesson above.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Lesson Modal -->
    <div id="editModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeEditModal()"></div>
            
            <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full p-6" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Edit Lesson</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="editLessonForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Title *</label>
                                <input type="text" name="title" id="edit_title" required
                                    class="w-full rounded-xl border-2 border-slate-200 px-4 py-2 text-sm text-gray-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Order</label>
                                <input type="number" name="sort_order" id="edit_sort_order" min="0"
                                    class="w-full rounded-xl border-2 border-slate-200 px-4 py-2 text-sm text-gray-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Summary</label>
                            <textarea name="summary" id="edit_summary" rows="2"
                                class="w-full rounded-xl border-2 border-slate-200 px-4 py-2 text-sm text-gray-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition resize-none"></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Provider *</label>
                                <select name="video_provider" id="edit_video_provider" required
                                    class="w-full rounded-xl border-2 border-slate-200 px-4 py-2 text-sm text-gray-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition">
                                    <option value="url">Direct URL</option>
                                    <option value="youtube">YouTube</option>
                                    <option value="vimeo">Vimeo</option>
                                    <option value="s3">AWS S3</option>
                                    <option value="cloudflare">Cloudflare</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Video URL *</label>
                                <input type="text" name="video_url" id="edit_video_url" required
                                    class="w-full rounded-xl border-2 border-slate-200 px-4 py-2 text-sm text-gray-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Duration (seconds)</label>
                                <input type="number" name="duration_seconds" id="edit_duration_seconds" min="0"
                                    class="w-full rounded-xl border-2 border-slate-200 px-4 py-2 text-sm text-gray-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition">
                            </div>
                        </div>
                        <div>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="is_free_preview" value="1" id="edit_is_free_preview"
                                    class="w-4 h-4 rounded border-slate-300 text-slate-600 focus:ring-slate-500">
                                <span class="ml-2 text-sm text-gray-700 font-medium">Free Preview</span>
                            </label>
                        </div>
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                            <button type="button" onclick="closeEditModal()" class="px-6 py-2 border-2 border-slate-300 rounded-xl text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-xl text-sm font-semibold text-white hover:from-indigo-700 hover:to-indigo-800 transition">
                                Update Lesson
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const lessonsData = @json($lessonsData);
        
        function openEditModal(lessonId) {
            const lesson = lessonsData[lessonId];
            if (!lesson) return;
            
            // Populate form
            document.getElementById('edit_title').value = lesson.title || '';
            document.getElementById('edit_summary').value = lesson.summary || '';
            document.getElementById('edit_sort_order').value = lesson.sort_order || '';
            document.getElementById('edit_video_provider').value = lesson.video_provider || 'url';
            document.getElementById('edit_video_url').value = lesson.video_url || '';
            document.getElementById('edit_duration_seconds').value = lesson.duration_seconds || '';
            document.getElementById('edit_is_free_preview').checked = lesson.is_free_preview || false;
            
            // Set form action
            document.getElementById('editLessonForm').action = '/teacher/lessons/' + lesson.id;
            
            // Show modal
            document.getElementById('editModal').classList.remove('hidden');
        }
        
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
        
        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditModal();
            }
        });
    </script>
</x-app-layout>
