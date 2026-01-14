<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Tutoring System') }} - Private Tutoring Booking Platform</title>
    <meta name="description" content="Book private tutoring sessions with expert teachers. Access recorded courses, manage your bookings, and learn at your own pace.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                    <span class="text-xl font-bold text-gray-900">Tutoring System</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 font-medium transition">Sign In</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-gradient-to-r from-slate-900 to-slate-800 text-white rounded-xl font-semibold hover:from-slate-800 hover:to-slate-700 transition shadow-md">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 py-20 lg:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
                    Learn from Expert Teachers
                    <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-emerald-600">
                        Anytime, Anywhere
                    </span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Book private tutoring sessions, access recorded courses, and connect with qualified teachers. 
                    Start your learning journey today.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-gradient-to-r from-slate-900 to-slate-800 text-white rounded-xl font-semibold text-lg hover:from-slate-800 hover:to-slate-700 transition shadow-lg transform hover:scale-105">
                        Start Learning Now
                    </a>
                    <a href="#features" class="px-8 py-4 bg-white text-slate-900 border-2 border-slate-300 rounded-xl font-semibold text-lg hover:bg-slate-50 transition shadow-md">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Why Choose Our Platform?
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Everything you need to succeed in your learning journey
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1: Book Sessions -->
                <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-lg hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Easy Booking</h3>
                    <p class="text-gray-600">
                        Book one-on-one tutoring sessions with expert teachers. Choose your preferred time slot and subject. 
                        Secure payment processing with multiple payment options.
                    </p>
                </div>

                <!-- Feature 2: Recorded Courses -->
                <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-lg hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Recorded Courses</h3>
                    <p class="text-gray-600">
                        Access comprehensive video courses at your own pace. Learn from expert teachers with structured lessons, 
                        progress tracking, and downloadable resources.
                    </p>
                </div>

                <!-- Feature 3: Expert Teachers -->
                <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-lg hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Expert Teachers</h3>
                    <p class="text-gray-600">
                        Learn from qualified and experienced teachers. View ratings and reviews, choose teachers that match 
                        your learning style, and get personalized attention.
                    </p>
                </div>

                <!-- Feature 4: Direct Messaging -->
                <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-lg hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Direct Messaging</h3>
                    <p class="text-gray-600">
                        Communicate directly with your teachers. Ask questions, share resources, and get instant support. 
                        All conversations are linked to your bookings for easy reference.
                    </p>
                </div>

                <!-- Feature 5: Resources & Materials -->
                <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-lg hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Resources & Materials</h3>
                    <p class="text-gray-600">
                        Access downloadable resources, study materials, and supplementary content. Teachers can share 
                        files, notes, and assignments directly through the platform.
                    </p>
                </div>

                <!-- Feature 6: Reviews & Ratings -->
                <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-lg hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.363-1.118l-2.8-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Reviews & Ratings</h3>
                    <p class="text-gray-600">
                        Share your learning experience and help others make informed decisions. Rate teachers, courses, 
                        and sessions. All reviews are verified and help maintain quality standards.
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
                    How It Works
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Get started in just a few simple steps
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold shadow-lg">
                        1
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Sign Up</h3>
                    <p class="text-gray-600">Create your free account in seconds. Choose your role as a student or teacher.</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold shadow-lg">
                        2
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Browse & Choose</h3>
                    <p class="text-gray-600">Explore subjects, view teacher profiles, ratings, and available time slots.</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold shadow-lg">
                        3
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Book & Pay</h3>
                    <p class="text-gray-600">Select your preferred time slot, complete secure payment, and confirm your booking.</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-amber-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold shadow-lg">
                        4
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Learn & Grow</h3>
                    <p class="text-gray-600">Attend your session, access resources, and continue learning with recorded courses.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-slate-900 to-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Ready to Start Learning?
            </h2>
            <p class="text-xl text-gray-300 mb-8">
                Join thousands of students already learning with our platform. 
                Get started today and unlock your potential.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-slate-900 rounded-xl font-semibold text-lg hover:bg-gray-100 transition shadow-lg transform hover:scale-105">
                    Create Free Account
                </a>
                <a href="{{ route('login') }}" class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-xl font-semibold text-lg hover:bg-white hover:text-slate-900 transition">
                    Sign In
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
                        <span class="text-lg font-bold text-gray-900">Tutoring System</span>
                    </div>
                    <p class="text-gray-600 text-sm">
                        Your trusted platform for private tutoring and online learning.
                    </p>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#features" class="text-gray-600 hover:text-gray-900 text-sm transition">Features</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-900 text-sm transition">Sign Up</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 text-sm transition">Sign In</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Support</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm transition">Help Center</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm transition">Contact Us</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm transition">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-200 text-center text-sm text-gray-600">
                <p>&copy; {{ date('Y') }} Tutoring System. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
