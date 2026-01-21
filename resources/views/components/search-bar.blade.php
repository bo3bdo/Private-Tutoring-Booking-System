@props(['placeholder' => __('common.Search...'), 'action' => null, 'method' => 'GET'])

<div class="relative">
    <div class="absolute inset-y-0 {{ app()->getLocale() === 'ar' ? 'right-0' : 'left-0' }} flex items-center pl-3 pr-3 pointer-events-none">
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </div>
    <form action="{{ $action ?? request()->url() }}" method="{{ $method }}" class="relative">
        <input
            type="search"
            name="search"
            value="{{ request('search') }}"
            placeholder="{{ $placeholder }}"
            class="block w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-dark-blue-500 focus:border-transparent transition duration-150"
            aria-label="{{ $placeholder }}"
        >
        @if(request('search'))
            <button
                type="button"
                onclick="this.form.search.value=''; this.form.submit();"
                class="absolute inset-y-0 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} flex items-center pr-3 pl-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                aria-label="{{ __('common.Clear search') }}"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        @endif
    </form>
</div>
