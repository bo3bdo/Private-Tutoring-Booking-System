<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create Badge') }}
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
                    <form method="POST" action="{{ route('admin.badges.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <!-- Slug -->
                            <div>
                                <x-input-label for="slug" :value="__('Slug')" />
                                <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Unique identifier for the badge (e.g., bronze-learner)') }}</p>
                            </div>

                            <!-- Tier -->
                            <div>
                                <x-input-label for="tier" :value="__('Tier')" />
                                <select id="tier" name="tier" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    @foreach($tiers as $tier)
                                    <option value="{{ $tier }}" {{ old('tier') == $tier ? 'selected' : '' }}>{{ ucfirst($tier) }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('tier')" />
                            </div>

                            <!-- Color -->
                            <div>
                                <x-input-label for="color" :value="__('Color')" />
                                <div class="flex items-center mt-1 space-x-2">
                                    <input type="color" id="color" name="color" value="{{ old('color', '#F59E0B') }}" class="h-10 w-20 rounded border-gray-300 dark:border-gray-700">
                                    <x-text-input type="text" name="color_text" :value="old('color', '#F59E0B')" class="w-full" placeholder="#F59E0B" />
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('color')" />
                            </div>

                            <!-- Icon -->
                            <div>
                                <x-input-label for="icon" :value="__('Icon (optional)')" />
                                <x-text-input id="icon" name="icon" type="text" class="mt-1 block w-full" :value="old('icon')" placeholder="medal" />
                                <x-input-error class="mt-2" :messages="$errors->get('icon')" />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Icon name (e.g., medal, trophy, star)') }}</p>
                            </div>

                            <!-- Is Active -->
                            <div class="flex items-center mt-8">
                                <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                <label for="is_active" class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Active') }}</label>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Create Badge') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sync color picker with text input
        document.getElementById('color').addEventListener('input', function(e) {
            document.querySelector('input[name="color_text"]').value = e.target.value;
        });
        document.querySelector('input[name="color_text"]').addEventListener('input', function(e) {
            document.getElementById('color').value = e.target.value;
        });

        // Auto-generate slug from name
        document.getElementById('name').addEventListener('input', function(e) {
            const slug = e.target.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
            if (!document.getElementById('slug').value) {
                document.getElementById('slug').value = slug;
            }
        });
    </script>
</x-app-layout>
