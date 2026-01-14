<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Course Sales & Payments
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl shadow-lg border-2 border-emerald-200 p-6">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-1">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalRevenue, 2) }} BHD</p>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl shadow-lg border-2 border-blue-200 p-6">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-1">Total Sales</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalSales }}</p>
                </div>
            </div>

            <!-- Purchases List -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Course Purchases</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchased At</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($purchases as $purchase)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $purchase->course->title }}</div>
                                            <div class="text-sm text-gray-500">{{ $purchase->course->subject->name }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $purchase->student->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $purchase->student->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                            {{ number_format($purchase->payment->amount ?? 0, 2) }} {{ $purchase->payment->currency ?? 'BHD' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $purchase->purchased_at->format('M j, Y g:i A') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">No purchases yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $purchases->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
