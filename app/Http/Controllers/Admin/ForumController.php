<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumCategory;
use App\Models\ForumPost;
use App\Models\ForumThread;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ForumController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_categories' => ForumCategory::count(),
            'active_categories' => ForumCategory::where('is_active', true)->count(),
            'total_threads' => ForumThread::count(),
            'total_posts' => ForumPost::count(),
            'recent_threads' => ForumThread::with(['user', 'category'])
                ->latest()
                ->limit(10)
                ->get(),
        ];

        return view('admin.forum.dashboard', compact('stats'));
    }

    // Category Management
    public function categories()
    {
        $categories = ForumCategory::withCount('threads')->orderBy('sort_order')->get();

        return view('admin.forum.categories', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.forum.create-category');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:forum_categories',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;

        while (ForumCategory::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        ForumCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.forum.categories')
            ->with('success', 'Category created successfully!');
    }

    public function editCategory(ForumCategory $category)
    {
        return view('admin.forum.edit-category', compact('category'));
    }

    public function updateCategory(Request $request, ForumCategory $category)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('forum_categories')->ignore($category->id),
            ],
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;

        while (ForumCategory::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.forum.categories')
            ->with('success', 'Category updated successfully!');
    }

    public function deleteCategory(ForumCategory $category)
    {
        if ($category->threads()->exists()) {
            return back()->with('error', 'Cannot delete category with existing threads. Move or delete the threads first.');
        }

        $category->delete();

        return redirect()->route('admin.forum.categories')
            ->with('success', 'Category deleted successfully!');
    }

    public function reorderCategories(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:forum_categories,id',
            'categories.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->categories as $categoryData) {
            ForumCategory::where('id', $categoryData['id'])
                ->update(['sort_order' => $categoryData['sort_order']]);
        }

        return response()->json(['success' => true]);
    }

    // Thread Management
    public function threads(Request $request)
    {
        $threads = ForumThread::with(['user', 'category'])
            ->withCount('posts')
            ->withReactions()
            ->latest()
            ->paginate(50);

        return view('admin.forum.threads', compact('threads'));
    }

    public function editThread(ForumThread $thread)
    {
        return view('admin.forum.edit-thread', compact('thread'));
    }

    public function updateThread(Request $request, ForumThread $thread)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:10000',
            'category_id' => 'required|exists:forum_categories,id',
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;

        while (ForumThread::where('slug', $slug)->where('id', '!=', $thread->id)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        $thread->update([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'category_id' => $request->category_id,
        ]);

        // Update the first post content if it changed
        $firstPost = $thread->posts()->oldest()->first();
        if ($firstPost && $firstPost->content !== $request->content) {
            $firstPost->update(['content' => $request->content]);
        }

        return redirect()->route('admin.forum.threads')
            ->with('success', 'Thread updated successfully!');
    }

    public function deleteThread(ForumThread $thread)
    {
        $thread->delete();

        return redirect()->route('admin.forum.threads')
            ->with('success', 'Thread deleted successfully!');
    }

    public function pinThread(ForumThread $thread)
    {
        $thread->update(['is_pinned' => ! $thread->is_pinned]);

        $action = $thread->is_pinned ? 'pinned' : 'unpinned';

        return response()->json([
            'success' => true,
            'message' => "Thread {$action} successfully!",
            'pinned' => $thread->is_pinned,
        ]);
    }

    public function lockThread(ForumThread $thread)
    {
        $thread->update(['is_locked' => ! $thread->is_locked]);

        $action = $thread->is_locked ? 'locked' : 'unlocked';

        return response()->json([
            'success' => true,
            'message' => "Thread {$action} successfully!",
            'locked' => $thread->is_locked,
        ]);
    }

    // Post Management
    public function posts(Request $request)
    {
        $posts = ForumPost::with(['user', 'thread'])
            ->withReactions()
            ->latest()
            ->paginate(50);

        return view('admin.forum.posts', compact('posts'));
    }

    public function editPost(ForumPost $post)
    {
        return view('admin.forum.edit-post', compact('post'));
    }

    public function updatePost(Request $request, ForumPost $post)
    {
        $request->validate([
            'content' => 'required|string|max:10000',
        ]);

        $post->update(['content' => $request->content]);

        return redirect()->route('admin.forum.posts')
            ->with('success', 'Post updated successfully!');
    }

    public function deletePost(ForumPost $post)
    {
        $thread = $post->thread;
        $post->delete();

        // Update thread's post count and last post
        $thread->updateLastPost();

        return redirect()->route('admin.forum.posts')
            ->with('success', 'Post deleted successfully!');
    }
}
