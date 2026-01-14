<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('teacher.courses.show', $course) }}" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Sales: {{ $course->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl shadow-lg border-2 border-emerald-200 p-6">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-1">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalRevenue, 2) }} {{ $course->currency }}</p>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl shadow-lg border-2 border-blue-200 p-6">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-1">Total Enrollments</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalEnrollments }}</p>
                </div>
            </div>

            <!-- Enrollments List -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Enrollments</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrolled At</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($enrollments as $enrollment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">{{ $enrollment->student->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $enrollment->student->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $enrollment->enrolled_at->format('M j, Y g:i A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @php
                                                $purchase = $course->purchases()->where('student_id', $enrollment->student_id)->with('payment')->first();
                                            @endphp
                                            @if($purchase && $purchase->payment)
                                                {{ number_format($purchase->payment->amount ?? 0, 2) }} {{ $course->currency }}
                                            @else
                                                Free
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center text-gray-500">No enrollments yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $enrollments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
