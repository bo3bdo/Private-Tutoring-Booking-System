<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('student.support-tickets.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                Create Support Ticket
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                <form method="POST" action="{{ route('student.support-tickets.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="subject" class="block text-sm font-semibold text-gray-900 mb-2">
                            Subject <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="subject" id="subject" required value="{{ old('subject') }}" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2 transition @error('subject') border-red-500 @enderror" placeholder="Brief description of your issue">
                        @error('subject')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="category" class="block text-sm font-semibold text-gray-900 mb-2">
                            Category
                        </label>
                        <select name="category" id="category" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2 transition">
                            <option value="">Select category...</option>
                            <option value="technical" {{ old('category') === 'technical' ? 'selected' : '' }}>Technical</option>
                            <option value="billing" {{ old('category') === 'billing' ? 'selected' : '' }}>Billing</option>
                            <option value="general" {{ old('category') === 'general' ? 'selected' : '' }}>General</option>
                            <option value="booking" {{ old('category') === 'booking' ? 'selected' : '' }}>Booking</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="priority" class="block text-sm font-semibold text-gray-900 mb-2">
                            Priority
                        </label>
                        <select name="priority" id="priority" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2 transition">
                            <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" id="description" rows="6" required class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2 transition resize-none @error('description') border-red-500 @enderror" placeholder="Please describe your issue in detail...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('student.support-tickets.index') }}" class="px-4 py-2 border-2 border-slate-300 rounded-xl text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-emerald-700 hover:to-emerald-800 transition">
                            Create Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
