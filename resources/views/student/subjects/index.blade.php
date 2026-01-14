<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                    Browse Subjects
                </h2>
                <p class="text-sm text-gray-600 mt-1">Select a subject to find available teachers</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($subjects->isEmpty())
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-12 text-center">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Subjects Available</h3>
                    <p class="text-gray-600">Check back later for new subjects.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($subjects as $subject)
                        <a href="{{ route('student.subjects.show', $subject) }}" class="group block bg-white rounded-2xl shadow-lg border-2 border-slate-200 overflow-hidden hover:shadow-xl hover:border-slate-300 transition-all duration-200 transform hover:-translate-y-1">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                    <div class="flex items-center gap-1 px-2.5 py-1 bg-emerald-50 rounded-full">
                                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                        </svg>
                                        <span class="text-xs font-semibold text-emerald-700">{{ $subject->teachers()->where('is_active', true)->count() }}</span>
                                    </div>
                                </div>
                                
                                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-emerald-600 transition">{{ $subject->name }}</h3>
                                
                                @if($subject->description)
                                    <p class="text-sm text-gray-600 line-clamp-2 mb-4">{{ $subject->description }}</p>
                                @endif

                                <div class="flex items-center text-sm font-semibold text-emerald-600 group-hover:text-emerald-700">
                                    <span>View Teachers</span>
                                    <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
