<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('student.subjects.courses', $course->subject) }}" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $course->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Course Header -->
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start gap-6">
                        @if($course->thumbnail_path)
                            <img src="{{ Storage::url($course->thumbnail_path) }}" alt="{{ $course->title }}" class="w-32 h-32 object-cover rounded-xl">
                        @endif
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $course->title }}</h1>
                            <p class="text-gray-600 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ $course->teacher->name }}
                            </p>
                            <div class="flex items-center gap-4">
                                <span class="text-3xl font-bold text-gray-900">{{ number_format($course->price, 2) }} {{ $course->currency }}</span>
                                <span class="text-sm text-gray-500">{{ $course->lessons->count() }} {{ __('common.lessons') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($course->description)
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('common.About This Course') }}</h3>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $course->description }}</p>
                </div>
            @endif

            <!-- Lessons -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('common.Course Lessons') }}</h3>
                    <div class="space-y-2">
                        @foreach($course->lessons as $index => $lesson)
                            <div class="flex items-center gap-4 p-4 border border-slate-200 rounded-xl {{ $lesson->is_free_preview ? 'bg-blue-50' : ($isEnrolled ? 'bg-white' : 'bg-gray-50') }}">
                                <div class="flex-shrink-0 w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center font-semibold text-gray-700">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-semibold text-gray-900">{{ $lesson->title }}</h4>
                                        @if($lesson->is_free_preview)
                                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-800">{{ __('common.Preview') }}</span>
                                        @endif
                                    </div>
                                    @if($lesson->summary)
                                        <p class="text-sm text-gray-600 mt-1">{{ $lesson->summary }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-1">{{ $lesson->durationFormatted() }}</p>
                                </div>
                                @if($lesson->is_free_preview || $isEnrolled)
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Purchase Button -->
            @if(!$isEnrolled)
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6" x-data="courseDiscountCode()">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('common.Purchase This Course') }}</h3>
                    
                    <!-- Discount Code Section -->
                    <div class="mb-6 p-4 bg-slate-50 rounded-xl">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ __('common.Have a discount code?') }}</h4>
                        
                        <div x-show="!applied" class="flex gap-3">
                            <input type="text" x-model="code" 
                                   placeholder="{{ __('common.Discount Code') }}"
                                   class="flex-1 px-4 py-2 border-2 border-slate-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:ring-0 transition">
                            <button type="button" @click="applyCode()" :disabled="loading || !code"
                                    class="px-4 py-2 bg-emerald-600 rounded-lg text-sm font-semibold text-white hover:bg-emerald-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!loading">{{ __('common.Apply') }}</span>
                                <span x-show="loading">...</span>
                            </button>
                        </div>

                        <div x-show="errorMessage" x-text="errorMessage" class="mt-2 text-sm text-red-600"></div>

                        <div x-show="applied" class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-lg border border-emerald-200">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ __('common.Discount Applied') }}</p>
                                    <p class="text-xs text-gray-600" x-text="'Code: ' + code.toUpperCase()"></p>
                                </div>
                                <button type="button" @click="removeCode()" 
                                        class="text-sm font-medium text-red-600 hover:text-red-700 transition">
                                    {{ __('common.Remove') }}
                                </button>
                            </div>

                            <div class="space-y-1 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('common.Subtotal') }}:</span>
                                    <span class="font-semibold text-gray-900" x-text="subtotal + ' {{ $course->currency }}'"></span>
                                </div>
                                <div class="flex justify-between text-emerald-600">
                                    <span>{{ __('common.Discount') }}:</span>
                                    <span class="font-semibold" x-text="'-' + discount + ' {{ $course->currency }}'"></span>
                                </div>
                                <div class="pt-1 border-t border-slate-200 flex justify-between font-bold">
                                    <span class="text-gray-900">{{ __('common.Final Amount') }}:</span>
                                    <span class="text-emerald-600" x-text="finalAmount + ' {{ $course->currency }}'"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('student.courses.purchase', $course) }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="discount_code" x-model="code" x-bind:disabled="!applied">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">{{ __('common.Payment Method') }}</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-slate-400 transition">
                                    <input type="radio" name="provider" value="stripe" class="mr-3" checked>
                                    <span class="font-semibold text-gray-900">{{ __('common.Stripe') }}</span>
                                </label>
                                <label class="flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-slate-400 transition">
                                    <input type="radio" name="provider" value="benefitpay" class="mr-3">
                                    <span class="font-semibold text-gray-900">{{ __('common.BenefitPay') }}</span>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-emerald-700 hover:to-emerald-800 transition">
                            <span x-show="!applied">{{ __('common.Purchase for') }} {{ number_format($course->price, 2) }} {{ $course->currency }}</span>
                            <span x-show="applied" x-text="'{{ __('common.Purchase for') }} ' + finalAmount + ' {{ $course->currency }}'"></span>
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <a href="{{ route('student.my-courses.learn', $course->slug) }}" class="block w-full text-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-blue-700 hover:to-blue-800 transition">
                        {{ __('common.Start Learning') }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function courseDiscountCode() {
            return {
                code: '',
                loading: false,
                applied: false,
                errorMessage: '',
                subtotal: {{ number_format($course->price, 2) }},
                discount: 0,
                finalAmount: {{ number_format($course->price, 2) }},

                async applyCode() {
                    if (!this.code) return;
                    
                    this.loading = true;
                    this.errorMessage = '';

                    try {
                        const response = await fetch('{{ route("student.courses.validate-discount", $course) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                code: this.code
                            })
                        });

                        const data = await response.json();

                        if (data.valid) {
                            this.applied = true;
                            this.discount = parseFloat(data.discount_amount).toFixed(2);
                            this.finalAmount = parseFloat(data.final_amount).toFixed(2);
                        } else {
                            this.errorMessage = data.message;
                        }
                    } catch (error) {
                        this.errorMessage = '{{ __("common.An error occurred.") }}';
                    } finally {
                        this.loading = false;
                    }
                },

                removeCode() {
                    this.code = '';
                    this.applied = false;
                    this.discount = 0;
                    this.finalAmount = this.subtotal;
                    this.errorMessage = '';
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
