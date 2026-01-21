<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 dark:from-blue-800 dark:via-indigo-900 dark:to-indigo-950 p-4 sm:p-6 lg:p-8 mb-4 sm:mb-6 lg:mb-8">
            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-900/90 via-indigo-900/90 to-blue-900/90 dark:from-indigo-950/95 dark:via-indigo-950/95 dark:to-indigo-950/95 z-10"></div>
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center bg-no-repeat opacity-30 dark:opacity-20"></div>
            </div>
            
            <!-- Content -->
            <div class="relative z-20 flex items-center justify-between">
                <div class="min-w-0 flex-1">
                    <h2 class="font-semibold text-xl sm:text-2xl lg:text-3xl text-white leading-tight drop-shadow-lg">
                        {{ __('common.Calendar') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-blue-100 dark:text-blue-200 mt-1 sm:mt-2 drop-shadow-md">
                        {{ auth()->user()->isAdmin() ? __('common.View all bookings and time slots') : (auth()->user()->isTeacher() ? __('common.Manage your schedule and bookings') : __('common.View your upcoming lessons')) }}
                    </p>
                </div>
                <div class="hidden md:block flex-shrink-0 ml-4">
                    <div class="w-16 h-16 lg:w-24 lg:h-24 bg-white/20 dark:bg-white/10 backdrop-blur-sm rounded-xl lg:rounded-2xl flex items-center justify-center border border-white/30 dark:border-white/20 shadow-lg">
                        <svg class="w-8 h-8 lg:w-12 lg:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8 -mt-4 sm:-mt-6 lg:-mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6">
                    <div id="calendar" class="calendar-container"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css' rel='stylesheet' />
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
        <style>
            .calendar-container {
                padding: 1rem;
            }
            
            /* FullCalendar Dark Mode Support */
            .fc {
                color: rgb(17, 24, 39);
            }
            
            .dark .fc {
                color: rgb(243, 244, 246);
            }
            
            .fc-theme-standard td, .fc-theme-standard th {
                border-color: rgb(229, 231, 235);
            }
            
            .dark .fc-theme-standard td, .dark .fc-theme-standard th {
                border-color: rgb(55, 65, 81);
            }
            
            .fc-button {
                background-color: rgb(59, 130, 246) !important;
                border-color: rgb(59, 130, 246) !important;
                color: white !important;
                padding: 0.5rem 1rem !important;
                border-radius: 0.5rem !important;
                font-weight: 500 !important;
                transition: all 0.2s !important;
            }
            
            .fc-button:hover {
                background-color: rgb(37, 99, 235) !important;
                border-color: rgb(37, 99, 235) !important;
                transform: translateY(-1px);
            }
            
            .fc-button-active {
                background-color: rgb(29, 78, 216) !important;
                border-color: rgb(29, 78, 216) !important;
            }
            
            .fc-today-button {
                background-color: rgb(34, 197, 94) !important;
                border-color: rgb(34, 197, 94) !important;
            }
            
            .fc-today-button:hover {
                background-color: rgb(22, 163, 74) !important;
                border-color: rgb(22, 163, 74) !important;
            }
            
            .fc-day-today {
                background-color: rgb(239, 246, 255) !important;
            }
            
            .dark .fc-day-today {
                background-color: rgb(30, 58, 138) !important;
            }
            
            .fc-event {
                border-radius: 0.375rem !important;
                padding: 0.25rem 0.5rem !important;
                font-size: 0.875rem !important;
                cursor: pointer !important;
                transition: all 0.2s !important;
            }
            
            .fc-event:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
            }
            
            .fc-daygrid-event {
                border: none !important;
            }
            
            .fc-col-header-cell {
                background-color: rgb(249, 250, 251);
                padding: 0.75rem 0.5rem;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.05em;
            }
            
            .dark .fc-col-header-cell {
                background-color: rgb(31, 41, 55);
            }
            
            .fc-daygrid-day {
                transition: background-color 0.2s;
            }
            
            .fc-daygrid-day:hover {
                background-color: rgb(249, 250, 251);
            }
            
            .dark .fc-daygrid-day:hover {
                background-color: rgb(31, 41, 55);
            }
            
            .fc-toolbar-title {
                font-size: 1.5rem !important;
                font-weight: 700 !important;
                color: rgb(17, 24, 39) !important;
            }
            
            .dark .fc-toolbar-title {
                color: rgb(243, 244, 246) !important;
            }
            
            .fc-daygrid-day-number {
                padding: 0.5rem !important;
                font-weight: 500 !important;
            }
            
            .fc-scrollgrid {
                border-radius: 0.5rem;
                overflow: hidden;
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const calendarEl = document.getElementById('calendar');
                
                if (!calendarEl) {
                    console.error('Calendar element not found');
                    return;
                }

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    firstDay: {{ app()->getLocale() === 'ar' ? 6 : 1 }},
                    locale: '{{ app()->getLocale() }}',
                    height: 'auto',
                    aspectRatio: 1.8,
                    events: function(fetchInfo, successCallback, failureCallback) {
                        fetch('{{ route("api.calendar.events") }}?start=' + fetchInfo.startStr + '&end=' + fetchInfo.endStr, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin'
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log('Calendar events loaded:', data);
                                successCallback(data);
                            })
                            .catch(error => {
                                console.error('Error loading calendar events:', error);
                                failureCallback(error);
                            });
                    },
                    eventClick: function(info) {
                        info.jsEvent.preventDefault();
                        if (info.event.extendedProps.type === 'booking') {
                            @if(auth()->user()->isStudent())
                                const url = '{{ route("student.bookings.show", ":id") }}'.replace(':id', info.event.extendedProps.booking_id);
                            @elseif(auth()->user()->isTeacher())
                                const url = '{{ route("teacher.bookings.show", ":id") }}'.replace(':id', info.event.extendedProps.booking_id);
                            @else
                                const url = '{{ route("admin.bookings.show", ":id") }}'.replace(':id', info.event.extendedProps.booking_id);
                            @endif
                            window.location.href = url;
                        }
                    },
                    eventDisplay: 'block',
                    eventTimeFormat: {
                        hour: 'numeric',
                        minute: '2-digit',
                        meridiem: 'short'
                    },
                    dayMaxEvents: 3,
                    moreLinkClick: 'popover',
                    eventMouseEnter: function(info) {
                        info.el.style.cursor = 'pointer';
                    }
                });
                
                calendar.render();
            });
        </script>
    @endpush
</x-app-layout>
