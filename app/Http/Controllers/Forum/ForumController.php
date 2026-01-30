<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\ForumCategory;
use App\Models\ForumPost;
use App\Models\ForumReaction;
use App\Models\ForumThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    public function index()
    {
        $categories = ForumCategory::active()
            ->ordered()
            ->withCount(['threads', 'threads as active_threads' => function ($query) {
                $query->where('last_post_at', '>', now()->subDays(7));
            }])
            ->get();

        $recentThreads = ForumThread::with(['user', 'category'])
            ->withReactions()
            ->latest('last_post_at')
            ->limit(5)
            ->get();

        return view('forum.index', compact('categories', 'recentThreads'));
    }

    public function category($slug)
    {
        $category = ForumCategory::active()
            ->where('slug', $slug)
            ->withCount(['threads', 'posts'])
            ->firstOrFail();

        $sort = request('sort', 'latest');
        $search = request('search');

        $threadsQuery = $category->threads()
            ->with(['user', 'reactions'])
            ->withReactions()
            ->withCount('posts');

        if ($search) {
            $threadsQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        switch ($sort) {
            case 'top':
                $threadsQuery->orderByDesc('upvotes_count');
                break;
            case 'replies':
                $threadsQuery->orderByDesc('posts_count');
                break;
            case 'latest':
            default:
                $threadsQuery->orderBy('is_pinned', 'desc')
                    ->orderByDesc('last_post_at');
                break;
        }

        $threads = $threadsQuery->paginate(20);

        return view('forum.category', compact('category', 'threads', 'sort', 'search'));
    }

    public function thread($slug)
    {
        $thread = ForumThread::where('slug', $slug)
            ->withReactions()
            ->with([
                'category',
                'user',
                'posts' => function ($query) {
                    $query->withReactions()->with('user')->oldest();
                },
                'reactions',
                'subscriptions' => function ($query) {
                    $query->where('user_id', Auth::id());
                },
            ])
            ->firstOrFail();

        $thread->incrementViews();

        $isSubscribed = Auth::check() && $thread->isSubscribedBy(Auth::user());
        $canPost = Auth::check() && ! $thread->is_locked;

        // Get user's reactions for this thread and posts
        $userReactions = [];
        if (Auth::check()) {
            $postIds = $thread->posts->pluck('id')->toArray();
            $allIds = array_merge([$thread->id], $postIds);

            $reactions = ForumReaction::where('user_id', Auth::id())
                ->where(function ($query) use ($thread, $postIds) {
                    $query->where(function ($q) use ($thread) {
                        $q->where('reactable_type', ForumThread::class)
                            ->where('reactable_id', $thread->id);
                    })->orWhere(function ($q) use ($postIds) {
                        $q->where('reactable_type', ForumPost::class)
                            ->whereIn('reactable_id', $postIds);
                    });
                })
                ->get();

            foreach ($reactions as $reaction) {
                $key = $reaction->reactable_type === ForumThread::class ? 'thread-'.$reaction->reactable_id : 'post-'.$reaction->reactable_id;
                $userReactions[$key] = $reaction->reaction_type;
            }
        }

        return view('forum.thread', compact('thread', 'isSubscribed', 'canPost', 'userReactions'));
    }

    public function createThread()
    {
        $categories = ForumCategory::active()->ordered()->get();

        return view('forum.create-thread', compact('categories'));
    }

    public function storeThread(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:forum_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:10000',
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;

        while (ForumThread::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        $thread = ForumThread::create([
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'last_post_at' => now(),
        ]);

        // Create the first post
        ForumPost::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->route('forum.thread', $thread->slug)
            ->with('success', __('forum.Thread created successfully!'));
    }

    public function createPost($slug)
    {
        $thread = ForumThread::where('slug', $slug)->firstOrFail();

        if ($thread->is_locked) {
            abort(403, __('forum.This thread is locked for new replies.'));
        }

        return view('forum.create-post', compact('thread'));
    }

    public function storePost(Request $request, $slug)
    {
        $thread = ForumThread::where('slug', $slug)->firstOrFail();

        if ($thread->is_locked) {
            abort(403, __('forum.This thread is locked for new replies.'));
        }

        $request->validate([
            'content' => 'required|string|max:10000',
        ]);

        ForumPost::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->route('forum.thread', $thread->slug)
            ->with('success', __('forum.Reply posted successfully!'));
    }

    public function toggleReaction(Request $request, $type, $id)
    {
        $reactable = null;
        $route = '';

        if ($type === 'thread') {
            $reactable = ForumThread::findOrFail($id);
            $route = route('forum.thread', $reactable->slug);
        } elseif ($type === 'post') {
            $reactable = ForumPost::findOrFail($id);
            $route = route('forum.thread', $reactable->thread->slug).'#post-'.$reactable->id;
        }

        if (! $reactable) {
            abort(404);
        }

        $reactionType = $request->input('reaction_type');

        if (! in_array($reactionType, ['upvote', 'downvote', 'like'])) {
            abort(400);
        }

        // Remove any existing vote if user is voting with a different vote type
        if (in_array($reactionType, ['upvote', 'downvote'])) {
            ForumReaction::removeVotes(Auth::user(), $reactable);
        }

        $reaction = ForumReaction::toggleReaction(Auth::user(), $reactable, $reactionType);

        // Reload the reactable to get updated counts
        $reactable->loadCount([
            'reactions as upvotes_count' => function ($query) {
                $query->where('reaction_type', 'upvote');
            },
            'reactions as downvotes_count' => function ($query) {
                $query->where('reaction_type', 'downvote');
            },
            'reactions as likes_count' => function ($query) {
                $query->where('reaction_type', 'like');
            },
        ]);

        return response()->json([
            'success' => true,
            'removed' => $reaction->wasRecentlyDeleted ?? false,
            'upvotes' => $reactable->upvotes_count ?? 0,
            'downvotes' => $reactable->downvotes_count ?? 0,
            'likes' => $reactable->likes_count ?? 0,
        ]);
    }

    public function toggleSubscription($slug)
    {
        $thread = ForumThread::where('slug', $slug)->firstOrFail();
        $user = Auth::user();

        if ($thread->isSubscribedBy($user)) {
            $thread->unsubscribe($user);
            $message = 'Unsubscribed from thread';
        } else {
            $thread->subscribe($user);
            $message = 'Subscribed to thread';
        }

        return response()->json([
            'success' => true,
            'subscribed' => $thread->isSubscribedBy($user),
            'message' => $message,
        ]);
    }

    public function markAsBestAnswer($threadSlug, $postId)
    {
        $thread = ForumThread::where('slug', $threadSlug)->firstOrFail();
        $post = ForumPost::findOrFail($postId);

        // Only thread owner or admin can mark as best answer
        if ($thread->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403);
        }

        // Post must belong to the thread
        if ($post->thread_id !== $thread->id) {
            abort(404);
        }

        $thread->markAsBestAnswer($post);

        return response()->json([
            'success' => true,
            'message' => 'Best answer marked successfully!',
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        if (! $search) {
            return redirect()->route('forum.home');
        }

        $threads = ForumThread::with(['user', 'category'])
            ->withReactions()
            ->withCount([
                'posts',
                'reactions as upvotes_count' => function ($query) {
                    $query->where('reaction_type', 'upvote');
                },
                'reactions as downvotes_count' => function ($query) {
                    $query->where('reaction_type', 'downvote');
                },
            ])
            ->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhereHas('posts', function ($postQuery) use ($search) {
                        $postQuery->where('content', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy('is_pinned', 'desc')
            ->orderByDesc('last_post_at')
            ->paginate(20);

        return view('forum.search', compact('threads', 'search'));
    }
}
