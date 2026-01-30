<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $thread->title }}
        </h2>
    </x-slot>

    <style>
        .vote-btn[data-voted="true"] {
            background-color: rgb(59 130 246) !important;
            color: white !important;
            opacity: 0.9;
            cursor: not-allowed !important;
        }
        .vote-btn[disabled] {
            pointer-events: none;
            opacity: 0.5;
            cursor: not-allowed !important;
        }
        @media (prefers-color-scheme: dark) {
            .vote-btn[data-voted="true"] {
                background-color: rgb(37 99 235) !important;
            }
        }
        
        /* Notification styles */
        .forum-notification {
            min-width: 300px;
            max-width: 500px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            animation: slideIn 0.3s ease-out;
            transition: opacity 0.3s ease-out, transform 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @media (prefers-color-scheme: dark) {
            .forum-notification {
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
            }
        }
    </style>

    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('forum.home') }}" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                {{ __('forum.Forum') }}
            </a>
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <a href="{{ route('forum.category', $thread->category->slug) }}" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                {{ $thread->category->name }}
            </a>
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="text-gray-900 dark:text-white font-medium">{{ Str::limit($thread->title, 50) }}</span>
        </nav>
    </div>

    <!-- Thread Content -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $thread->title }}</h1>
                    @if($thread->is_pinned)
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded-full">
                            📌 {{ __('forum.Pinned') }}
                        </span>
                    @endif
                    @if($thread->is_locked)
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded-full">
                            🔒 {{ __('forum.Locked') }}
                        </span>
                    @endif
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('forum.by :user', ['user' => $thread->user->name]) }} • 
                    {{ $thread->created_at->diffForHumans() }} • 
                    {{ $thread->views_count }} {{ __('forum.views') }}
                </div>
            </div>

            <!-- Admin Actions -->
            @if(auth()->check() && auth()->user()->isAdmin())
                <div class="flex items-center gap-2">
                    <button type="button" onclick="event.preventDefault(); togglePin({{ $thread->id }});" id="pin-btn-{{ $thread->id }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $thread->is_pinned ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 hover:bg-yellow-200 dark:hover:bg-yellow-800' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z" />
                        </svg>
                        <span id="pin-text-{{ $thread->id }}">{{ $thread->is_pinned ? __('forum.Unpin Thread') : __('forum.Pin Thread') }}</span>
                    </button>

                    <button type="button" onclick="event.preventDefault(); toggleLock({{ $thread->id }});" id="lock-btn-{{ $thread->id }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $thread->is_locked ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 hover:bg-red-200 dark:hover:bg-red-800' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                        <span id="lock-text-{{ $thread->id }}">{{ $thread->is_locked ? __('forum.Unlock Thread') : __('forum.Lock Thread') }}</span>
                    </button>
                </div>
            @endif
        </div>

        <div class="prose dark:prose-invert max-w-none forum-content">
            {!! \App\Helpers\ForumHelper::parseMentions($thread->content, \App\Helpers\ForumHelper::threadUsers($thread)) !!}
        </div>

        <!-- Vote Buttons -->
        @php
            $threadKey = 'thread-' . $thread->id;
            $hasUpvoted = isset($userReactions[$threadKey]) && $userReactions[$threadKey] === 'upvote';
            $hasDownvoted = isset($userReactions[$threadKey]) && $userReactions[$threadKey] === 'downvote';
            $hasVoted = $hasUpvoted || $hasDownvoted;
        @endphp
        <div class="flex items-center gap-4 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <button type="button" 
                    id="upvote-thread-{{ $thread->id }}"
                    data-voted="{{ $hasUpvoted ? 'true' : 'false' }}"
                    onclick="event.preventDefault(); vote({{ $thread->id }}, 'thread', 'upvote');" 
                    {{ $hasVoted ? 'disabled' : '' }}
                    class="vote-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $hasUpvoted ? 'bg-blue-500 text-white dark:bg-blue-600 opacity-90 cursor-not-allowed' : 'text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600' }} {{ $hasDownvoted ? 'opacity-50 cursor-not-allowed' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
                <span id="upvotes-{{ $thread->id }}">{{ $thread->upvotes_count ?? 0 }}</span>
            </button>

            <button type="button" 
                    id="downvote-thread-{{ $thread->id }}"
                    data-voted="{{ $hasDownvoted ? 'true' : 'false' }}"
                    onclick="event.preventDefault(); vote({{ $thread->id }}, 'thread', 'downvote');" 
                    {{ $hasVoted ? 'disabled' : '' }}
                    class="vote-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $hasDownvoted ? 'bg-blue-500 text-white dark:bg-blue-600 opacity-90 cursor-not-allowed' : 'text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600' }} {{ $hasUpvoted ? 'opacity-50 cursor-not-allowed' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
                <span id="downvotes-{{ $thread->id }}">{{ $thread->downvotes_count ?? 0 }}</span>
            </button>
        </div>
    </div>

    <!-- Posts -->
    <div class="space-y-6">
        @foreach($thread->posts as $post)
            <div id="post-{{ $post->id }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $post->user->name }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $post->created_at->diffForHumans() }}
                        </div>
                        @if($post->id === $thread->best_answer_post_id)
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full">
                                ✓ {{ __('forum.Best Answer') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="prose dark:prose-invert max-w-none mb-4 forum-content">
                    {!! \App\Helpers\ForumHelper::parseMentions($post->content, \App\Helpers\ForumHelper::threadUsers($thread)) !!}
                </div>

                <!-- Post Vote Buttons -->
                @php
                    $postKey = 'post-' . $post->id;
                    $hasUpvotedPost = isset($userReactions[$postKey]) && $userReactions[$postKey] === 'upvote';
                    $hasDownvotedPost = isset($userReactions[$postKey]) && $userReactions[$postKey] === 'downvote';
                    $hasVotedPost = $hasUpvotedPost || $hasDownvotedPost;
                @endphp
                <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" 
                            id="upvote-post-{{ $post->id }}"
                            data-voted="{{ $hasUpvotedPost ? 'true' : 'false' }}"
                            onclick="event.preventDefault(); vote({{ $post->id }}, 'post', 'upvote');" 
                            {{ $hasVotedPost ? 'disabled' : '' }}
                            class="vote-btn inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-lg transition-colors {{ $hasUpvotedPost ? 'bg-blue-500 text-white dark:bg-blue-600 opacity-90 cursor-not-allowed' : 'text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600' }} {{ $hasDownvotedPost ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                        <span id="upvotes-{{ $post->id }}">{{ $post->upvotes_count ?? 0 }}</span>
                    </button>

                    <button type="button" 
                            id="downvote-post-{{ $post->id }}"
                            data-voted="{{ $hasDownvotedPost ? 'true' : 'false' }}"
                            onclick="event.preventDefault(); vote({{ $post->id }}, 'post', 'downvote');" 
                            {{ $hasVotedPost ? 'disabled' : '' }}
                            class="vote-btn inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-lg transition-colors {{ $hasDownvotedPost ? 'bg-blue-500 text-white dark:bg-blue-600 opacity-90 cursor-not-allowed' : 'text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600' }} {{ $hasUpvotedPost ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                        <span id="downvotes-{{ $post->id }}">{{ $post->downvotes_count ?? 0 }}</span>
                    </button>

                    @if(auth()->check() && auth()->id() === $thread->user_id && !$thread->best_answer_post_id && $post->id !== $thread->best_answer_post_id)
                        <button type="button" onclick="event.preventDefault(); markAsBestAnswer({{ $post->id }});" class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 rounded-lg transition-colors ml-auto">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            {{ __('forum.Mark as Best Answer') }}
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Reply Button -->
    @if($canPost)
        <div class="mt-6">
            <a href="{{ route('forum.create-post', $thread->slug) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                {{ __('forum.Reply to Thread') }}
            </a>
        </div>
    @elseif($thread->is_locked)
        <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg">
            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                🔒 {{ __('forum.This thread is locked for new replies.') }}
            </p>
        </div>
    @endif

    <script>
        function showNotification(type, title, message) {
            console.log('Showing notification:', {type, title, message});
            
            // Create notification element
            const notification = document.createElement('div');
            const isRTL = document.documentElement.dir === 'rtl';
            
            notification.className = `forum-notification fixed top-4 ${isRTL ? 'left-4' : 'right-4'} z-[9999] flex items-center gap-3 p-4 rounded-lg ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${type === 'success' ? 
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />' :
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
                        }
                    </svg>
                    <div class="flex-1">
                        <div class="font-bold">${title}</div>
                        <div class="text-sm opacity-90">${message}</div>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            console.log('Notification added to body');
            
            // Remove after 4 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(-20px)';
                setTimeout(() => notification.remove(), 300);
            }, 4000);
        }

        async function togglePin(threadId) {
            try {
                const response = await fetch(`/admin/forum/threads/${threadId}/pin`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('success', '{{ __('forum.Success') }}', data.message || '{{ __('forum.Thread pinned successfully!') }}');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification('error', '{{ __('forum.Error') }}', data.message || '{{ __('forum.Something went wrong') }}');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('error', '{{ __('forum.Error') }}', '{{ __('forum.Connection error') }}');
            }
        }

        async function toggleLock(threadId) {
            try {
                const response = await fetch(`/admin/forum/threads/${threadId}/lock`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('success', '{{ __('forum.Success') }}', data.message || '{{ __('forum.Thread locked successfully!') }}');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification('error', '{{ __('forum.Error') }}', data.message || '{{ __('forum.Something went wrong') }}');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('error', '{{ __('forum.Error') }}', '{{ __('forum.Connection error') }}');
            }
        }

        async function vote(id, type, reactionType) {
            const btnId = `${reactionType}-${type}-${id}`;
            const btn = document.getElementById(btnId);
            const oppositeType = reactionType === 'upvote' ? 'downvote' : 'upvote';
            const oppositeBtn = document.getElementById(`${oppositeType}-${type}-${id}`);
            
            // Check if already voted - if yes, do nothing
            const isVoted = btn.getAttribute('data-voted') === 'true';
            if (isVoted) {
                console.log('Already voted, button disabled');
                return; // Don't send request if already voted
            }
            
            console.log('Vote action:', {id, type, reactionType});
            
            try {
                const response = await fetch(`/forum/react/${type}/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        reaction_type: reactionType
                    })
                });
                
                const data = await response.json();
                console.log('Vote response:', data);
                
                if (data.success) {
                    // Update counts
                    const upvotesEl = document.getElementById(`upvotes-${id}`);
                    const downvotesEl = document.getElementById(`downvotes-${id}`);
                    
                    if (upvotesEl) upvotesEl.textContent = data.upvotes || 0;
                    if (downvotesEl) downvotesEl.textContent = data.downvotes || 0;
                    
                    // Activate this button and disable it
                    btn.setAttribute('data-voted', 'true');
                    btn.disabled = true;
                    btn.classList.remove('bg-gray-100', 'text-gray-700', 'dark:bg-gray-700', 'dark:text-gray-300', 'hover:bg-gray-200', 'dark:hover:bg-gray-600');
                    btn.classList.add('bg-blue-500', 'text-white', 'dark:bg-blue-600', 'opacity-90', 'cursor-not-allowed');
                    
                    // Disable opposite button
                    if (oppositeBtn) {
                        oppositeBtn.disabled = true;
                        oppositeBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                } else {
                    showNotification('error', '{{ __('forum.Error') }}', '{{ __('forum.Vote failed') }}');
                }
            } catch (error) {
                console.error('Vote error:', error);
                showNotification('error', '{{ __('forum.Error') }}', '{{ __('forum.Connection error') }}');
            }
        }

        async function markAsBestAnswer(postId) {
            if (!confirm('{{ __('forum.Mark as Best Answer') }}?')) return;
            
            try {
                const response = await fetch(`/forum/best-answer/{{ $thread->slug }}/${postId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('success', '{{ __('forum.Success') }}', data.message || '{{ __('forum.Best answer marked successfully!') }}');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification('error', '{{ __('forum.Error') }}', data.message || '{{ __('forum.Something went wrong') }}');
                }
            } catch (error) {
                console.error('Best answer error:', error);
                showNotification('error', '{{ __('forum.Error') }}', '{{ __('forum.Connection error') }}');
            }
        }

        // Test CSRF token on page load
        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                console.log('CSRF token found:', token.content.substring(0, 20) + '...');
            } else {
                console.error('CSRF token NOT found!');
            }
        });
    </script>
</x-app-layout>
