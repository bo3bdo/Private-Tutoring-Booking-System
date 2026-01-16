<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                    {{ __('common.Reviews Management') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">{{ __('common.Approve or reject user reviews') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Tabs -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 mb-6 overflow-hidden">
                <div class="p-4">
                    <div class="flex items-center gap-2 overflow-x-auto">
                        <a href="{{ route('admin.reviews.index') }}" class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ !request('status') ? 'bg-slate-900 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            {{ __('common.All') }}
                        </a>
                        <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('status') === 'pending' ? 'bg-amber-600 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            {{ __('common.Pending') }}
                        </a>
                        <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('status') === 'approved' ? 'bg-green-600 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            {{ __('common.Approved') }}
                        </a>
                    </div>
                </div>
            </div>

            @if($reviews->isEmpty())
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                    <p class="text-gray-600 text-lg">{{ __('common.No reviews found') }}</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($reviews as $review)
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="flex items-center gap-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.363-1.118l-2.8-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $review->is_approved ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                                {{ $review->is_approved ? __('common.Approved') : __('common.Pending') }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-2">{{ __('common.By:') }} <span class="font-semibold">{{ $review->user->name }}</span></p>
                                        @if($review->comment)
                                            <p class="text-sm text-gray-700 mb-2">{{ $review->comment }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500">{{ $review->created_at->format('M j, Y g:i A') }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if(!$review->is_approved)
                                            <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-xl text-sm font-semibold hover:bg-green-700 transition">
                                                    {{ __('common.Approve') }}
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" class="inline" onsubmit="return confirm('{{ __('common.Are you sure you want to delete this review?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition">
                                                {{ __('common.Delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
