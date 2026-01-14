<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subjects') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($subjects as $subject)
                            <a href="{{ route('student.subjects.show', $subject) }}" class="block rounded-xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition-shadow">
                                <h3 class="text-lg font-semibold text-slate-800">{{ $subject->name }}</h3>
                                @if($subject->description)
                                    <p class="mt-2 text-sm text-slate-600">{{ Str::limit($subject->description, 100) }}</p>
                                @endif
                                <div class="mt-4 text-sm text-slate-500">
                                    {{ $subject->teachers()->where('is_active', true)->count() }} teachers available
                                </div>
                            </a>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <p class="text-slate-500">No subjects available at the moment.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
