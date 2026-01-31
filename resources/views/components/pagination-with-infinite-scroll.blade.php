@props(['items', 'perPage' => 15, 'enableInfiniteScroll' => false])

<div x-data="infiniteScroll(@json($enableInfiniteScroll), @json($perPage))" class="space-y-4">
    <!-- Items Container -->
    <div id="items-container" class="space-y-4">
        @forelse($items as $item)
            {{ $slot }}
        @empty
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                </div>
                <p class="text-gray-500 dark:text-gray-400">{{ __('common.No items found') }}</p>
            </div>
        @endforelse
    </div>

    <!-- Loading Indicator -->
    <div x-show="loading" x-transition class="flex justify-center py-8">
        <div class="flex items-center gap-2">
            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce"></div>
            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
        </div>
    </div>

    <!-- Traditional Pagination (shown when infinite scroll disabled) -->
    @if(!$enableInfiniteScroll)
        <div class="mt-6">
            {{ $items->links() }}
        </div>
    @endif

    <!-- Infinite Scroll Trigger -->
    @if($enableInfiniteScroll)
        <div id="infinite-scroll-trigger" class="py-8"></div>
    @endif
</div>

@push('scripts')
<script>
    function infiniteScroll(enableInfiniteScroll, perPage) {
        return {
            loading: false,
            currentPage: 1,
            hasMorePages: @json($items->hasMorePages()),
            enableInfiniteScroll: enableInfiniteScroll,
            perPage: perPage,
            init() {
                if (this.enableInfiniteScroll) {
                    this.setupIntersectionObserver();
                }
            },
            setupIntersectionObserver() {
                const trigger = document.getElementById('infinite-scroll-trigger');
                if (!trigger) return;

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && this.hasMorePages && !this.loading) {
                            this.loadMore();
                        }
                    });
                }, { threshold: 0.1 });

                observer.observe(trigger);
            },
            async loadMore() {
                this.loading = true;
                this.currentPage++;

                try {
                    const response = await fetch(
                        `${window.location.pathname}?page=${this.currentPage}&ajax=true`,
                        { headers: { 'X-Requested-With': 'XMLHttpRequest' } }
                    );

                    if (response.ok) {
                        const html = await response.text();
                        const container = document.getElementById('items-container');
                        
                        // Parse the response to get new items
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newItems = doc.querySelector('#items-container')?.innerHTML;

                        if (newItems) {
                            container.insertAdjacentHTML('beforeend', newItems);
                            
                            // Check if there are more pages
                            this.hasMorePages = doc.querySelector('[data-has-more-pages]')?.dataset.hasMorePages === 'true';
                        }
                    }
                } catch (error) {
                    console.error('Error loading more items:', error);
                } finally {
                    this.loading = false;
                }
            }
        };
    }
</script>
@endpush
