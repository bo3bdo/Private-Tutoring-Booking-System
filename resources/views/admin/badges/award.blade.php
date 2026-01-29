<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Award Badge') }}: {{ $badge->name }}
            </h2>
            <a href="{{ route('admin.badges.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                {{ __('Back to Badges') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6 flex items-center space-x-4">
                        <div class="h-16 w-16 rounded-full flex items-center justify-center" style="background-color: {{ $badge->color }}">
                            <svg class="h-10 w-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $badge->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $badge->description }}</p>
                            <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                                {{ $badge->tier === 'bronze' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : '' }}
                                {{ $badge->tier === 'silver' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}
                                {{ $badge->tier === 'gold' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                {{ $badge->tier === 'platinum' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                            ">
                                {{ $badge->tier }}
                            </span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.badges.award', $badge) }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="user_id" :value="__('Select User')" />
                            <select id="user_id" name="user_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">{{ __('Select a user...') }}</option>
                                @php
                                $users = \App\Models\User::whereDoesntHave('userBadges', function($query) use ($badge) {
                                    $query->where('badge_id', $badge->id);
                                })->orderBy('name')->get();
                                @endphp
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Award Badge') }}</x-primary-button>
                            <a href="{{ route('admin.badges.users', $badge) }}" class="text-gray-600 dark:text-gray-400 hover:underline">
                                {{ __('View Awarded Users') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
