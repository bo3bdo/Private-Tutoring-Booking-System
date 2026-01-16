<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', __('common.Tutoring System')) }} - {{ __('common.Private Tutoring Booking Platform') }}</title>
    <meta name="description" content="{{ __('common.Book private tutoring sessions with expert teachers. Access recorded courses, manage your bookings, and learn at your own pace.') }}">
    
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
<body class="font-sans antialiased bg-white">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">{{ __('common.Tutoring System') }}</span>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Language Switcher -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 text-gray-700 hover:text-gray-900 font-medium transition rounded-lg hover:bg-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                            </svg>
                            <span class="hidden sm:inline">{{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
                            <a href="{{ route('locale.switch', 'en') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition {{ app()->getLocale() === 'en' ? 'bg-blue-50 text-blue-700 font-semibold' : '' }}">
                                <div class="flex items-center gap-2">
                                    <span>🇬🇧</span>
                                    <span>English</span>
                                    @if(app()->getLocale() === 'en')
                                        <svg class="w-4 h-4 ml-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </div>
                            </a>
                            <a href="{{ route('locale.switch', 'ar') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition {{ app()->getLocale() === 'ar' ? 'bg-blue-50 text-blue-700 font-semibold' : '' }}">
                                <div class="flex items-center gap-2">
                                    <span>🇸🇦</span>
                                    <span>العربية</span>
                                    @if(app()->getLocale() === 'ar')
                                        <svg class="w-4 h-4 ml-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </div>
                            </a>
                        </div>
                    </div>
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 font-medium transition">{{ __('common.Sign In') }}</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-gradient-to-r from-slate-900 to-slate-800 text-white rounded-xl font-semibold hover:from-slate-800 hover:to-slate-700 transition shadow-md">
                        {{ __('common.Get Started') }}
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative py-20 lg:py-32 overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900/80 via-blue-900/70 to-slate-800/80 z-10"></div>
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2071&q=80')] bg-cover bg-center bg-no-repeat"></div>
        </div>
        
        <!-- Content -->
        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 drop-shadow-lg">
                    {{ __('common.Learn from Expert Teachers') }}
                    <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-emerald-300">
                        {{ __('common.Anytime, Anywhere') }}
                    </span>
                </h1>
                <p class="text-xl text-gray-100 mb-8 max-w-3xl mx-auto drop-shadow-md">
                    {{ __('common.Book private tutoring sessions, access recorded courses, and connect with qualified teachers. Start your learning journey today.') }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-gradient-to-r from-slate-900 to-slate-800 text-white rounded-xl font-semibold text-lg hover:from-slate-800 hover:to-slate-700 transition shadow-lg transform hover:scale-105 backdrop-blur-sm bg-white/10 border border-white/20">
                        {{ __('common.Start Learning Now') }}
                    </a>
                    <a href="#features" class="px-8 py-4 bg-white/90 text-slate-900 border-2 border-white/50 rounded-xl font-semibold text-lg hover:bg-white transition shadow-md backdrop-blur-sm">
                        {{ __('common.Learn More') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-20 bg-gradient-to-br from-slate-50 to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 md:p-12">
                <div class="text-center mb-8">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        {{ __('common.About Our Platform') }}
                    </h2>
                </div>
                <div class="max-w-4xl mx-auto space-y-6">
                    <p class="text-lg text-gray-700 leading-relaxed">
                        {{ __('common.Welcome to the leading private tutoring platform that connects students with expert teachers. Our mission is to make quality education accessible to everyone, anytime and anywhere. Whether you are looking for one-on-one tutoring sessions, recorded courses, or comprehensive learning resources, we provide all the tools you need to succeed in your educational journey.') }}
                    </p>
                    <p class="text-lg text-gray-700 leading-relaxed">
                        {{ __('common.Our platform offers flexible scheduling, secure payment options, direct communication with teachers, and a comprehensive library of learning materials. Join thousands of satisfied students and teachers who are already benefiting from our innovative approach to online education.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    {{ __('common.Why Choose Our Platform?') }}
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('common.Everything you need to succeed in your learning journey') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1: Book Sessions -->
                <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-lg hover:shadow-xl transition overflow-hidden group">
                    <div class="relative h-48 mb-6 rounded-xl overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="{{ __('common.Easy Booking') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-600/80 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 w-14 h-14 bg-white/90 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('common.Easy Booking') }}</h3>
                    <p class="text-gray-600">
                        {{ __('common.Book one-on-one tutoring sessions with expert teachers. Choose your preferred time slot and subject. Secure payment processing with multiple payment options.') }}
                    </p>
                </div>

                <!-- Feature 2: Recorded Courses -->
                <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-lg hover:shadow-xl transition overflow-hidden group">
                    <div class="relative h-48 mb-6 rounded-xl overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="{{ __('common.Recorded Courses') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-emerald-600/80 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 w-14 h-14 bg-white/90 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('common.Recorded Courses') }}</h3>
                    <p class="text-gray-600">
                        {{ __('common.Access comprehensive video courses at your own pace. Learn from expert teachers with structured lessons, progress tracking, and downloadable resources.') }}
                    </p>
                </div>

                <!-- Feature 3: Expert Teachers -->
                <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-lg hover:shadow-xl transition overflow-hidden group">
                    <div class="relative h-48 mb-6 rounded-xl overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="{{ __('common.Expert Teachers') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-purple-600/80 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 w-14 h-14 bg-white/90 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('common.Expert Teachers') }}</h3>
                    <p class="text-gray-600">
                        {{ __('common.Learn from qualified and experienced teachers. View ratings and reviews, choose teachers that match your learning style, and get personalized attention.') }}
                    </p>
                </div>

                <!-- Feature 4: Direct Messaging -->
                <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-lg hover:shadow-xl transition overflow-hidden group">
                    <div class="relative h-48 mb-6 rounded-xl overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1577563908411-5077b6dc7624?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="{{ __('common.Direct Messaging') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-amber-600/80 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 w-14 h-14 bg-white/90 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('common.Direct Messaging') }}</h3>
                    <p class="text-gray-600">
                        {{ __('common.Communicate directly with your teachers. Ask questions, share resources, and get instant support. All conversations are linked to your bookings for easy reference.') }}
                    </p>
                </div>

                <!-- Feature 5: Resources & Materials -->
                <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-lg hover:shadow-xl transition overflow-hidden group">
                    <div class="relative h-48 mb-6 rounded-xl overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="{{ __('common.Resources & Materials') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-red-600/80 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 w-14 h-14 bg-white/90 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('common.Resources & Materials') }}</h3>
                    <p class="text-gray-600">
                        {{ __('common.Access downloadable resources, study materials, and supplementary content. Teachers can share files, notes, and assignments directly through the platform.') }}
                    </p>
                </div>

                <!-- Feature 6: Reviews & Ratings -->
                <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-lg hover:shadow-xl transition overflow-hidden group">
                    <div class="relative h-48 mb-6 rounded-xl overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="{{ __('common.Reviews & Ratings') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-yellow-600/80 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 w-14 h-14 bg-white/90 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.363-1.118l-2.8-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('common.Reviews & Ratings') }}</h3>
                    <p class="text-gray-600">
                        {{ __('common.Share your learning experience and help others make informed decisions. Rate teachers, courses, and sessions. All reviews are verified and help maintain quality standards.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 bg-gradient-to-br from-slate-50 to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    {{ __('common.How It Works') }}
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('common.Get started in just a few simple steps') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center group">
                    <div class="relative mb-6 mx-auto w-32 h-32 rounded-2xl overflow-hidden shadow-lg group-hover:shadow-xl transition">
                        <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="{{ __('common.Sign Up') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/60 to-blue-800/60 flex items-center justify-center">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-blue-600 text-2xl font-bold shadow-lg">
                                1
                            </div>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('common.Sign Up') }}</h3>
                    <p class="text-gray-600">{{ __('common.Create your free account in seconds. Choose your role as a student or teacher.') }}</p>
                </div>

                <div class="text-center group">
                    <div class="relative mb-6 mx-auto w-32 h-32 rounded-2xl overflow-hidden shadow-lg group-hover:shadow-xl transition">
                        <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="{{ __('common.Browse & Choose') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/60 to-emerald-800/60 flex items-center justify-center">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-emerald-600 text-2xl font-bold shadow-lg">
                                2
                            </div>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('common.Browse & Choose') }}</h3>
                    <p class="text-gray-600">{{ __('common.Explore subjects, view teacher profiles, ratings, and available time slots.') }}</p>
                </div>

                <div class="text-center group">
                    <div class="relative mb-6 mx-auto w-32 h-32 rounded-2xl overflow-hidden shadow-lg group-hover:shadow-xl transition">
                        <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="{{ __('common.Book & Pay') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/60 to-purple-800/60 flex items-center justify-center">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-purple-600 text-2xl font-bold shadow-lg">
                                3
                            </div>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('common.Book & Pay') }}</h3>
                    <p class="text-gray-600">{{ __('common.Select your preferred time slot, complete secure payment, and confirm your booking.') }}</p>
                </div>

                <div class="text-center group">
                    <div class="relative mb-6 mx-auto w-32 h-32 rounded-2xl overflow-hidden shadow-lg group-hover:shadow-xl transition">
                        <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="{{ __('common.Learn & Grow') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-amber-600/60 to-amber-800/60 flex items-center justify-center">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-amber-600 text-2xl font-bold shadow-lg">
                                4
                            </div>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('common.Learn & Grow') }}</h3>
                    <p class="text-gray-600">{{ __('common.Attend your session, access resources, and continue learning with recorded courses.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="relative py-20 overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 to-slate-800/90 z-10"></div>
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=2071&q=80')] bg-cover bg-center bg-no-repeat"></div>
        </div>
        
        <!-- Content -->
        <div class="relative z-20 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 drop-shadow-lg">
                {{ __('common.Ready to Start Learning?') }}
            </h2>
            <p class="text-xl text-gray-100 mb-8 drop-shadow-md">
                {{ __('common.Join thousands of students already learning with our platform. Get started today and unlock your potential.') }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-slate-900 rounded-xl font-semibold text-lg hover:bg-gray-100 transition shadow-lg transform hover:scale-105">
                    {{ __('common.Create Free Account') }}
                </a>
                <a href="{{ route('login') }}" class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-xl font-semibold text-lg hover:bg-white hover:text-slate-900 transition backdrop-blur-sm">
                    {{ __('common.Sign In') }}
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ __('common.Tutoring System') }}</span>
                    </div>
                    <p class="text-gray-600 text-sm">
                        {{ __('common.Your trusted platform for private tutoring and online learning.') }}
                    </p>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">{{ __('common.Quick Links') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="#features" class="text-gray-600 hover:text-gray-900 text-sm transition">{{ __('common.Features') }}</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-900 text-sm transition">{{ __('common.Sign Up') }}</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 text-sm transition">{{ __('common.Sign In') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">{{ __('common.Support') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm transition">{{ __('common.Help Center') }}</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm transition">{{ __('common.Contact Us') }}</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm transition">{{ __('common.Privacy Policy') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-200 text-center text-sm text-gray-600">
                <p>&copy; {{ date('Y') }} {{ __('common.Tutoring System') }}. {{ __('common.All rights reserved.') }}</p>
            </div>
        </div>
    </footer>
</body>
</html>
