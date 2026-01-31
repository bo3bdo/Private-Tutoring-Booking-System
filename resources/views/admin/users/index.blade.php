<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="min-w-0 flex-1">
                <h2 class="font-semibold text-lg sm:text-xl lg:text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ __('common.Users Management') }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.View and manage all users') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8" id="users-container">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Mobile Card View -->
            <div class="block sm:hidden space-y-3" id="mobile-container">
                @include('admin.users._user-rows', ['users' => $users])
            </div>

            <!-- Desktop Table View -->
            <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.User') }}</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Email') }}</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Role') }}</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">{{ __('common.Registered At') }}</th>
                                    <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="desktop-container">
                                @include('admin.users._user-rows', ['users' => $users])
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Load more button (visible when more pages exist) -->
            <div class="mt-4 text-center hidden" id="load-more-wrap">
                <button type="button" id="load-more-btn" class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition shadow-lg">
                    {{ __('common.Load More') }}
                </button>
            </div>

            <!-- Loading indicator -->
            <div class="mt-4 text-center hidden" id="loading-indicator">
                <div class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400">
                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>{{ __('common.Loading...') }}</span>
                </div>
            </div>

            <!-- End message -->
            <div class="mt-4 text-center text-gray-600 dark:text-gray-400 text-sm hidden" id="end-message">
                {{ __('common.No more users to load') }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentPage = {{ $users->currentPage() }};
            let hasMore = {{ $users->hasMorePages() ? 'true' : 'false' }};
            let isLoading = false;

            const loadingIndicator = document.getElementById('loading-indicator');
            const endMessage = document.getElementById('end-message');
            const loadMoreWrap = document.getElementById('load-more-wrap');
            const loadMoreBtn = document.getElementById('load-more-btn');
            const mobileContainer = document.getElementById('mobile-container');
            const desktopContainer = document.getElementById('desktop-container');

            if (hasMore) {
                loadMoreWrap.classList.remove('hidden');
            }

            function updateUi() {
                if (hasMore) {
                    loadMoreWrap.classList.remove('hidden');
                    endMessage.classList.add('hidden');
                } else {
                    loadMoreWrap.classList.add('hidden');
                    endMessage.classList.remove('hidden');
                }
            }

            function handleScroll() {
                if (isLoading || !hasMore) return;
                const el = document.getElementById('main-content') || document.documentElement;
                const scrollTop = el.scrollTop ?? window.scrollY;
                const scrollHeight = el.scrollHeight ?? document.documentElement.scrollHeight;
                const clientHeight = el.clientHeight ?? window.innerHeight;
                if ((scrollHeight - (scrollTop + clientHeight)) < 400) {
                    loadMore();
                }
            }

            function loadMore() {
                if (isLoading || !hasMore) return;

                isLoading = true;
                currentPage++;
                loadingIndicator.classList.remove('hidden');
                loadMoreWrap.classList.add('hidden');
                endMessage.classList.add('hidden');

                fetch(`{{ route('admin.users.load-more') }}?page=${currentPage}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Load more failed');
                    return response.json();
                })
                .then(data => {
                    if (data.html_mobile) {
                        const wrap = document.createElement('div');
                        wrap.innerHTML = data.html_mobile;
                        wrap.querySelectorAll('.user-row').forEach(row => mobileContainer.appendChild(row.cloneNode(true)));
                    }
                    if (data.html_desktop) {
                        const wrap = document.createElement('tbody');
                        wrap.innerHTML = data.html_desktop;
                        wrap.querySelectorAll('tr').forEach(tr => desktopContainer.appendChild(tr.cloneNode(true)));
                    }

                    hasMore = data.has_more;
                    isLoading = false;
                    loadingIndicator.classList.add('hidden');
                    updateUi();
                })
                .catch(error => {
                    console.error('Error loading more users:', error);
                    isLoading = false;
                    currentPage--;
                    loadingIndicator.classList.add('hidden');
                    updateUi();
                });
            }

            if (loadMoreBtn) loadMoreBtn.addEventListener('click', loadMore);

            const mainContent = document.getElementById('main-content');
            if (mainContent) mainContent.addEventListener('scroll', handleScroll);
            window.addEventListener('scroll', handleScroll);
        });
    </script>
    @endpush
</x-app-layout>
