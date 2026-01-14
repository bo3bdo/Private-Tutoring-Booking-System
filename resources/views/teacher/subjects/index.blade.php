<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Subjects') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-6">
                        Select the subjects you can teach. Students will only see you for the subjects you select.
                    </p>

                    <form method="POST" action="{{ route('teacher.subjects.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            @forelse($allSubjects as $subject)
                                <label class="flex items-start p-4 border border-slate-200 rounded-lg hover:bg-slate-50 cursor-pointer transition">
                                    <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                                        {{ in_array($subject->id, $teacherSubjects) ? 'checked' : '' }}
                                        class="mt-1 rounded border-slate-300 text-slate-600 focus:ring-slate-500">
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-semibold text-gray-900">{{ $subject->name }}</p>
                                            @if(in_array($subject->id, $teacherSubjects))
                                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800">
                                                    Selected
                                                </span>
                                            @endif
                                        </div>
                                        @if($subject->description)
                                            <p class="text-sm text-gray-600 mt-1">{{ $subject->description }}</p>
                                        @endif
                                    </div>
                                </label>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <p>No active subjects available.</p>
                                    <p class="text-sm mt-2">Please contact the administrator to add subjects.</p>
                                </div>
                            @endforelse
                        </div>

                        @if($allSubjects->isNotEmpty())
                            <div class="mt-6 flex items-center gap-3">
                                <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                                    Save Changes
                                </button>
                                <a href="{{ route('teacher.dashboard') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-slate-50">
                                    Cancel
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
