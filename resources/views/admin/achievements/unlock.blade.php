<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Unlock Achievement') }}: {{ $achievement->name }}
            </h2>
            <a href="{{ route('admin.achievements.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                {{ __('Back to Achievements') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6 flex items-center space-x-4">
                        <div class="h-16 w-16 rounded-full flex items-center justify-center" style="background-color: {{ $achievement->color }}">
                            <svg class="h-10 w-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $achievement->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $achievement->description }}</p>
                            <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                +{{ $achievement->points }} {{ __('points') }}
                            </span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.achievements.unlock', $achievement) }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="user_id" :value="__('Select User')" />
                            <select id="user_id" name="user_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">{{ __('Select a user...') }}</option>
                                @php
                                $users = \App\Models\User::whereDoesntHave('userAchievements', function($query) use ($achievement) {
                                    $query->where('achievement_id', $achievement->id)->whereNotNull('unlocked_at');
                                })->orderBy('name')->get();
                                @endphp
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Unlock Achievement') }}</x-primary-button>
                            <a href="{{ route('admin.achievements.users', $achievement) }}" class="text-gray-600 dark:text-gray-400 hover:underline">
                                {{ __('View Unlocked Users') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
