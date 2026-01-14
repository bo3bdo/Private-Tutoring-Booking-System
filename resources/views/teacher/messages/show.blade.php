<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('teacher.messages.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold shadow-md">
                    {{ substr($otherUser->name, 0, 1) }}
                </div>
                <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                    {{ $otherUser->name }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <!-- Messages -->
                <div class="h-96 overflow-y-auto p-6 space-y-4 bg-slate-50">
                    @foreach($messages as $message)
                        <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-md">
                                <div class="flex items-center gap-2 mb-1 {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                    <span class="text-xs font-semibold text-gray-600">{{ $message->sender->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $message->created_at->format('g:i A') }}</span>
                                </div>
                                <div class="p-4 rounded-2xl {{ $message->sender_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-white border border-slate-200 text-gray-900' }}">
                                    <p class="text-sm whitespace-pre-wrap">{{ $message->body }}</p>
                                    @if($message->attachments->isNotEmpty())
                                        <div class="mt-3 space-y-2">
                                            @foreach($message->attachments as $attachment)
                                                <a href="{{ route('teacher.message-attachments.download', $attachment) }}" target="_blank" class="flex items-center gap-2 text-sm {{ $message->sender_id === auth()->id() ? 'text-blue-100 hover:text-white' : 'text-blue-600 hover:text-blue-700' }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                    </svg>
                                                    {{ $attachment->file_name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Message Form -->
                <div class="p-6 border-t border-slate-200 bg-white">
                    <form method="POST" action="{{ route('teacher.messages.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                        <div class="mb-4">
                            <textarea name="body" rows="3" required placeholder="Type your message..." class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition resize-none"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Attachments (optional)</label>
                            <input type="file" name="attachments[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-1">Max 5 files, 10MB each</p>
                        </div>
                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-blue-700 hover:to-blue-800 transition">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
