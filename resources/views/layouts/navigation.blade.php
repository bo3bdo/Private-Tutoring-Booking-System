<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isTeacher() ? route('teacher.dashboard') : route('student.dashboard')) }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-dark-blue-600 to-dark-blue-800 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-gray-900 dark:text-white hidden sm:block">{{ __('common.Tutoring System') }}</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(auth()->user()->isAdmin())
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('common.Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.courses.*')">
                            {{ __('common.Courses') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.subjects.index')" :active="request()->routeIs('admin.subjects.*')">
                            {{ __('common.Subjects') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.locations.index')" :active="request()->routeIs('admin.locations.*')">
                            {{ __('common.Locations') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.teachers.index')" :active="request()->routeIs('admin.teachers.*')">
                            {{ __('common.Teachers') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            {{ __('common.Users') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.reviews.index')" :active="request()->routeIs('admin.reviews.*')" :badge="auth()->user()->pendingReviewsCount()">
                            {{ __('common.Reviews') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.support-tickets.index')" :active="request()->routeIs('admin.support-tickets.*')" :badge="auth()->user()->totalUnreadSupportTicketsCount()">
                            {{ __('common.Support Tickets') }}
                        </x-nav-link>
                    @elseif(auth()->user()->isTeacher())
                        <x-nav-link :href="route('teacher.dashboard')" :active="request()->routeIs('teacher.dashboard')">
                            {{ __('common.Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('teacher.courses.index')" :active="request()->routeIs('teacher.courses.*') || request()->routeIs('teacher.lessons.*')">
                            {{ __('common.Courses') }}
                        </x-nav-link>
                        <x-nav-link :href="route('teacher.subjects.index')" :active="request()->routeIs('teacher.subjects.*')">
                            {{ __('common.My Subjects') }}
                        </x-nav-link>
                        <x-nav-link :href="route('teacher.slots.index')" :active="request()->routeIs('teacher.slots.*')">
                            {{ __('common.Manage Slots') }}
                        </x-nav-link>
                        <x-nav-link :href="route('teacher.availability.index')" :active="request()->routeIs('teacher.availability.*')">
                            {{ __('common.Availability') }}
                        </x-nav-link>
                        <x-nav-link :href="route('teacher.bookings.index')" :active="request()->routeIs('teacher.bookings.*')" :badge="auth()->user()->pendingBookingsCount()">
                            {{ __('common.Bookings') }}
                        </x-nav-link>
                        <x-nav-link :href="route('teacher.messages.index')" :active="request()->routeIs('teacher.messages.*')" :badge="auth()->user()->totalUnreadMessagesCount()">
                            {{ __('common.Messages') }}
                        </x-nav-link>
                        <x-nav-link :href="route('teacher.resources.index')" :active="request()->routeIs('teacher.resources.*')">
                            {{ __('common.Resources') }}
                        </x-nav-link>
                        <x-nav-link :href="route('teacher.support-tickets.index')" :active="request()->routeIs('teacher.support-tickets.*')" :badge="auth()->user()->totalUnreadSupportTicketsCount()">
                            {{ __('common.Support Tickets') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')">
                            {{ __('common.Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('student.subjects.index')" :active="request()->routeIs('student.subjects.*')">
                            {{ __('common.Subjects') }}
                        </x-nav-link>
                        <x-nav-link :href="route('student.my-courses.index')" :active="request()->routeIs('student.my-courses.*') || request()->routeIs('student.courses.*')">
                            {{ __('common.Recorded Courses') }}
                        </x-nav-link>
                        <x-nav-link :href="route('student.bookings.index')" :active="request()->routeIs('student.bookings.*')" :badge="auth()->user()->pendingBookingsCount()">
                            {{ __('common.My Bookings') }}
                        </x-nav-link>
                        <x-nav-link :href="route('student.messages.index')" :active="request()->routeIs('student.messages.*')" :badge="auth()->user()->totalUnreadMessagesCount()">
                            {{ __('common.Messages') }}
                        </x-nav-link>
                        <x-nav-link :href="route('student.resources.index')" :active="request()->routeIs('student.resources.*')">
                            {{ __('common.Resources') }}
                        </x-nav-link>
                        <x-nav-link :href="route('student.support-tickets.index')" :active="request()->routeIs('student.support-tickets.*')" :badge="auth()->user()->totalUnreadSupportTicketsCount()">
                            {{ __('common.Support') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                <!-- Dark Mode Toggle -->
                <button @click="$dispatch('toggle-dark-mode')" class="inline-flex items-center justify-center p-2 rounded-button text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-dark-blue-500 focus:ring-offset-2 transition-colors" aria-label="Toggle dark mode">
                    <!-- Moon Icon (Dark Mode) -->
                    <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <!-- Sun Icon (Light Mode) -->
                    <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </button>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-button text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Language Switcher -->
                        <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                            <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">{{ __('Language') }}</div>
                            <div class="flex gap-2">
                                <a href="{{ route('locale.switch', 'en') }}" class="flex-1 px-3 py-2 text-sm rounded-button transition {{ app()->getLocale() === 'en' ? 'bg-dark-blue-100 dark:bg-dark-blue-900 text-dark-blue-700 dark:text-dark-blue-300 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    {{ __('English') }}
                                </a>
                                <a href="{{ route('locale.switch', 'ar') }}" class="flex-1 px-3 py-2 text-sm rounded-button transition {{ app()->getLocale() === 'ar' ? 'bg-dark-blue-100 dark:bg-dark-blue-900 text-dark-blue-700 dark:text-dark-blue-300 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    {{ __('Arabic') }}
                                </a>
                            </div>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('common.Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('common.Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center gap-2 sm:hidden">
                <!-- Sidebar Toggle Button (Mobile) -->
                <button @click="$dispatch('sidebar-toggle')" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 transition duration-150 ease-in-out" aria-label="Toggle sidebar">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Dark Mode Toggle (Mobile) -->
                <button @click="$dispatch('toggle-dark-mode')" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-colors" aria-label="Toggle dark mode">
                    <!-- Moon Icon (Dark Mode) -->
                    <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <!-- Sun Icon (Light Mode) -->
                    <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('common.Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.courses.*')">
                    {{ __('common.Courses') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.subjects.index')" :active="request()->routeIs('admin.subjects.*')">
                    {{ __('common.Subjects') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.locations.index')" :active="request()->routeIs('admin.locations.*')">
                    {{ __('common.Locations') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.teachers.index')" :active="request()->routeIs('admin.teachers.*')">
                    {{ __('common.Teachers') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('common.Users') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.reviews.index')" :active="request()->routeIs('admin.reviews.*')" :badge="auth()->user()->pendingReviewsCount()">
                    {{ __('common.Reviews') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.support-tickets.index')" :active="request()->routeIs('admin.support-tickets.*')" :badge="auth()->user()->totalUnreadSupportTicketsCount()">
                    {{ __('common.Support Tickets') }}
                </x-responsive-nav-link>
            @elseif(auth()->user()->isTeacher())
                <x-responsive-nav-link :href="route('teacher.dashboard')" :active="request()->routeIs('teacher.*')">
                    {{ __('common.Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('teacher.courses.index')" :active="request()->routeIs('teacher.courses.*') || request()->routeIs('teacher.lessons.*')">
                    {{ __('common.Courses') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('teacher.bookings.index')" :active="request()->routeIs('teacher.bookings.*')" :badge="auth()->user()->pendingBookingsCount()">
                    {{ __('common.Bookings') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('teacher.messages.index')" :active="request()->routeIs('teacher.messages.*')" :badge="auth()->user()->totalUnreadMessagesCount()">
                    {{ __('common.Messages') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('teacher.resources.index')" :active="request()->routeIs('teacher.resources.*')">
                    {{ __('common.Resources') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')">
                    {{ __('common.Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('student.subjects.index')" :active="request()->routeIs('student.subjects.*')">
                    {{ __('common.Subjects') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('student.my-courses.index')" :active="request()->routeIs('student.my-courses.*') || request()->routeIs('student.courses.*')">
                    {{ __('common.Recorded Courses') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('student.bookings.index')" :active="request()->routeIs('student.bookings.*')" :badge="auth()->user()->pendingBookingsCount()">
                    {{ __('common.My Bookings') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('student.messages.index')" :active="request()->routeIs('student.messages.*')" :badge="auth()->user()->totalUnreadMessagesCount()">
                    {{ __('common.Messages') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('student.resources.index')" :active="request()->routeIs('student.resources.*')">
                    {{ __('common.Resources') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('student.support-tickets.index')" :active="request()->routeIs('student.support-tickets.*')" :badge="auth()->user()->totalUnreadSupportTicketsCount()">
                    {{ __('common.Support') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Language Switcher -->
                <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 mb-2">
                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">{{ __('Language') }}</div>
                    <div class="flex gap-2">
                        <a href="{{ route('locale.switch', 'en') }}" class="flex-1 px-3 py-2 text-sm rounded-button transition {{ app()->getLocale() === 'en' ? 'bg-dark-blue-100 dark:bg-dark-blue-900 text-dark-blue-700 dark:text-dark-blue-300 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            {{ __('English') }}
                        </a>
                        <a href="{{ route('locale.switch', 'ar') }}" class="flex-1 px-3 py-2 text-sm rounded-button transition {{ app()->getLocale() === 'ar' ? 'bg-dark-blue-100 dark:bg-dark-blue-900 text-dark-blue-700 dark:text-dark-blue-300 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            {{ __('Arabic') }}
                        </a>
                    </div>
                </div>

                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('common.Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('common.Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
