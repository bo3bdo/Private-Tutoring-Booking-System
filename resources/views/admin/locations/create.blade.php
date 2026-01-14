<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.locations.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Add New Location
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Location Information</h3>
                        <p class="text-sm text-gray-600">Add a new meeting location for in-person lessons</p>
                    </div>

                    <form method="POST" action="{{ route('admin.locations.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                                Location Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition @error('name') border-red-500 @enderror"
                                placeholder="e.g., Main Office, Branch Office">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-semibold text-gray-900 mb-2">
                                Address <span class="text-red-500">*</span>
                            </label>
                            <textarea name="address" id="address" rows="3" required
                                class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition resize-none @error('address') border-red-500 @enderror"
                                placeholder="Full address including street, city, country">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="map_url" class="block text-sm font-semibold text-gray-900 mb-2">
                                Map URL <span class="text-gray-400 font-normal">(Optional)</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                </div>
                                <input type="url" name="map_url" id="map_url" value="{{ old('map_url') }}"
                                    class="w-full pl-12 rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition @error('map_url') border-red-500 @enderror"
                                    placeholder="https://maps.google.com/...">
                            </div>
                            @error('map_url')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-semibold text-gray-900 mb-2">
                                Notes <span class="text-gray-400 font-normal">(Optional)</span>
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition resize-none @error('notes') border-red-500 @enderror"
                                placeholder="Additional notes or instructions about this location...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-slate-300 text-slate-600 focus:ring-slate-500">
                                <span class="ml-2 text-sm text-gray-700 font-medium">Active</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1 ml-6">Only active locations will be available for selection</p>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
                            <a href="{{ route('admin.locations.index') }}" class="inline-flex items-center justify-center px-6 py-3 border-2 border-slate-300 rounded-xl text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-400 transition">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-slate-900 to-slate-800 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-slate-800 hover:to-slate-700 transform hover:scale-105 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Create Location
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
