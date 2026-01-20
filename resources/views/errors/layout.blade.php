<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val)); $watch('darkMode', val => document.documentElement.classList.toggle('dark', val)); darkMode && document.documentElement.classList.add('dark'); $watch('$el.classList', () => { darkMode = document.documentElement.classList.contains('dark'); })" @toggle-dark-mode.window="darkMode = !darkMode">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title') - {{ config('app.name', __('common.Tutoring System')) }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        @if(app()->getLocale() === 'ar')
            <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700&display=swap" rel="stylesheet" />
        @else
            <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @endif

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-200">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 sm:px-6 lg:px-8">
            <!-- Navigation -->
            <nav class="absolute top-0 left-0 right-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-slate-900 to-slate-800 dark:from-blue-600 dark:to-blue-800 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-gray-900 dark:text-white">{{ __('common.Tutoring System') }}</span>
                        </a>
                        <div class="flex items-center gap-4">
                            <button @click="$dispatch('toggle-dark-mode')" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-colors" aria-label="Toggle dark mode">
                                <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </button>
                            <a href="{{ route('welcome') }}" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white font-medium transition">
                                {{ __('common.Home') }}
                            </a>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Error Content -->
            <div class="flex-1 flex items-center justify-center w-full max-w-2xl">
                <div class="text-center">
                    <div class="mb-8">
                        <div class="inline-flex items-center justify-center w-32 h-32 rounded-full bg-gradient-to-br from-red-500 to-red-600 dark:from-red-600 dark:to-red-700 mb-6 shadow-2xl">
                            <span class="text-6xl font-bold text-white">@yield('code', 'Error')</span>
                        </div>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                        @yield('message', __('common.Error'))
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                        @yield('description', __('common.Sorry, something went wrong. Please try again later.'))
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('welcome') }}" class="px-6 py-3 bg-gradient-to-r from-slate-900 to-slate-800 dark:from-blue-600 dark:to-blue-700 text-white rounded-xl font-semibold hover:from-slate-800 hover:to-slate-700 dark:hover:from-blue-700 dark:hover:to-blue-800 transition shadow-lg">
                            {{ __('common.Go Home') }}
                        </a>
                        <button onclick="window.history.back()" class="px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            {{ __('common.Go Back') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
