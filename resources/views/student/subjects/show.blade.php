<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('student.subjects.index') }}" class="text-blue-600 hover:text-blue-800">Subjects</a> / {{ $subject->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $subject->name }}</h1>
                    @if($subject->description)
                        <p class="mt-2 text-gray-600">{{ $subject->description }}</p>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Available Teachers</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($teachers as $teacher)
                            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                                <h3 class="font-semibold text-slate-800">{{ $teacher->user->name }}</h3>
                                @if($teacher->bio)
                                    <p class="mt-1 text-sm text-slate-600">{{ Str::limit($teacher->bio, 80) }}</p>
                                @endif
                                <div class="mt-3 flex items-center gap-2">
                                    @if($teacher->supports_online)
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-800">Online</span>
                                    @endif
                                    @if($teacher->supports_in_person)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-800">In Person</span>
                                    @endif
                                </div>
                                <a href="{{ route('student.teachers.slots', $teacher) }}?subject_id={{ $subject->id }}" class="mt-3 inline-block rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                                    View Available Slots
                                </a>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-8">
                                <p class="text-slate-500">No teachers available for this subject.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
