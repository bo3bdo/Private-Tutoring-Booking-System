<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isTeacher() ? route('teacher.dashboard') : route('student.dashboard')) }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-gray-900 hidden sm:block">Tutoring System</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(auth()->user()->isAdmin())
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.courses.*')">
                            {{ __('Courses') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.subjects.index')" :active="request()->routeIs('admin.subjects.*')">
                            {{ __('Subjects') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.locations.index')" :active="request()->routeIs('admin.locations.*')">
                            {{ __('Locations') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.teachers.index')" :active="request()->routeIs('admin.teachers.*')">
                            {{ __('Teachers') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.reviews.index')" :active="request()->routeIs('admin.reviews.*')" :badge="auth()->user()->pendingReviewsCount()">
                            {{ __('Reviews') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.support-tickets.index')" :active="request()->routeIs('admin.support-tickets.*')" :badge="auth()->user()->totalUnreadSupportTicketsCount()">
                            {{ __('Support Tickets') }}
                        </x-nav-link>
                    @elseif(auth()->user()->isTeacher())
                        <x-nav-link :href="route('teacher.dashboard')" :active="request()->routeIs('teacher.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('teacher.courses.index')" :active="request()->routeIs('teacher.courses.*') || request()->routeIs('teacher.lessons.*')">
                            {{ __('Courses') }}
                        </x-nav-link>
                        <x-nav-link :href="route('teacher.subjects.index')" :active="request()->routeIs('teacher.subjects.*')">
                            {{ __('My Subjects') }}
                        </x-nav-link>
                        <x-nav-link :href="route('teacher.slots.index')" :active="request()->routeIs('teacher.slots.*')">
                            {{ __('Manage Slots') }}
                        </x-nav-link>
                        <x-nav-link :href="route('teacher.availability.index')" :active="request()->routeIs('teacher.availability.*')">
                            {{ __('Availability') }}
                        </x-nav-link>
                        <x-nav-link :href="route('teacher.bookings.index')" :active="request()->routeIs('teacher.bookings.*')" :badge="auth()->user()->pendingBookingsCount()">
                            {{ __('Bookings') }}
                        </x-nav-link>
                        <x-nav-link :href="route('teacher.messages.index')" :active="request()->routeIs('teacher.messages.*')" :badge="auth()->user()->totalUnreadMessagesCount()">
                            {{ __('Messages') }}
                        </x-nav-link>
                        <x-nav-link :href="route('teacher.resources.index')" :active="request()->routeIs('teacher.resources.*')">
                            {{ __('Resources') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('student.subjects.index')" :active="request()->routeIs('student.subjects.*')">
                            {{ __('Subjects') }}
                        </x-nav-link>
                        <x-nav-link :href="route('student.my-courses.index')" :active="request()->routeIs('student.my-courses.*') || request()->routeIs('student.courses.*')">
                            {{ __('Recorded Courses') }}
                        </x-nav-link>
                        <x-nav-link :href="route('student.bookings.index')" :active="request()->routeIs('student.bookings.*')" :badge="auth()->user()->pendingBookingsCount()">
                            {{ __('My Bookings') }}
                        </x-nav-link>
                        <x-nav-link :href="route('student.messages.index')" :active="request()->routeIs('student.messages.*')" :badge="auth()->user()->totalUnreadMessagesCount()">
                            {{ __('Messages') }}
                        </x-nav-link>
                        <x-nav-link :href="route('student.resources.index')" :active="request()->routeIs('student.resources.*')">
                            {{ __('Resources') }}
                        </x-nav-link>
                        <x-nav-link :href="route('student.support-tickets.index')" :active="request()->routeIs('student.support-tickets.*')" :badge="auth()->user()->totalUnreadSupportTicketsCount()">
                            {{ __('Support') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.courses.*')">
                    {{ __('Courses') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.subjects.index')" :active="request()->routeIs('admin.subjects.*')">
                    {{ __('Subjects') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.locations.index')" :active="request()->routeIs('admin.locations.*')">
                    {{ __('Locations') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.teachers.index')" :active="request()->routeIs('admin.teachers.*')">
                    {{ __('Teachers') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.reviews.index')" :active="request()->routeIs('admin.reviews.*')" :badge="auth()->user()->pendingReviewsCount()">
                    {{ __('Reviews') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.support-tickets.index')" :active="request()->routeIs('admin.support-tickets.*')" :badge="auth()->user()->totalUnreadSupportTicketsCount()">
                    {{ __('Support Tickets') }}
                </x-responsive-nav-link>
            @elseif(auth()->user()->isTeacher())
                <x-responsive-nav-link :href="route('teacher.dashboard')" :active="request()->routeIs('teacher.*')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('teacher.courses.index')" :active="request()->routeIs('teacher.courses.*') || request()->routeIs('teacher.lessons.*')">
                    {{ __('Courses') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('teacher.bookings.index')" :active="request()->routeIs('teacher.bookings.*')" :badge="auth()->user()->pendingBookingsCount()">
                    {{ __('Bookings') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('teacher.messages.index')" :active="request()->routeIs('teacher.messages.*')" :badge="auth()->user()->totalUnreadMessagesCount()">
                    {{ __('Messages') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('teacher.resources.index')" :active="request()->routeIs('teacher.resources.*')">
                    {{ __('Resources') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('student.subjects.index')" :active="request()->routeIs('student.subjects.*')">
                    {{ __('Subjects') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('student.my-courses.index')" :active="request()->routeIs('student.my-courses.*') || request()->routeIs('student.courses.*')">
                    {{ __('Recorded Courses') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('student.bookings.index')" :active="request()->routeIs('student.bookings.*')" :badge="auth()->user()->pendingBookingsCount()">
                    {{ __('My Bookings') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('student.messages.index')" :active="request()->routeIs('student.messages.*')" :badge="auth()->user()->totalUnreadMessagesCount()">
                    {{ __('Messages') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('student.resources.index')" :active="request()->routeIs('student.resources.*')">
                    {{ __('Resources') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('student.support-tickets.index')" :active="request()->routeIs('student.support-tickets.*')" :badge="auth()->user()->totalUnreadSupportTicketsCount()">
                    {{ __('Support') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
