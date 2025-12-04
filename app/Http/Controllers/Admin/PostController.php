<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        $query = Post::with(['user', 'categories']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Filter by author
        if ($request->has('author')) {
            $query->where('user_id', $request->author);
        }

        // Search
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        $posts = $query->latest()->paginate(20);
        $categories = Category::all();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.posts.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'required|in:draft,published,archived',
            'featured' => 'boolean',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'published_at' => 'nullable|date'
        ]);

        $post = $this->postService->create([
            'title' => $request->title,
            'content' => $request->content,
            'excerpt' => $request->excerpt,
            'image' => $request->hasFile('image') ? $request->file('image') : null,
            'status' => $request->status,
            'featured' => $request->boolean('featured'),
            'categories' => $request->categories ?? [],
            'tags' => $request->tags ?? [],
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'published_at' => $request->published_at
        ], auth()->user());


        return redirect()->route('admin.posts.index')
            ->with('success', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        $post->load(['user', 'categories', 'tags', 'comments.user', 'likes.user']);

        $stats = [
            'views' => $post->views,
            'likes' => $post->likes_count,
            'comments' => $post->comments_count,
            'bookmarks' => $post->bookmarks()->count(),
        ];

        return view('admin.posts.show', compact('post', 'stats'));
    }

    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'required|in:draft,published,archived',
            'featured' => 'boolean',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'published_at' => 'nullable|date'
        ]);

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'excerpt' => $request->excerpt,
            'status' => $request->status,
            'featured' => $request->boolean('featured'),
            'categories' => $request->categories ?? [],
            'tags' => $request->tags ?? [],
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'published_at' => $request->published_at
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }

        $this->postService->update($post, $data);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $this->postService->delete($post);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post deleted successfully!');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,draft,archive,delete,featured,unfeatured',
            'posts' => 'required|array',
            'posts.*' => 'exists:posts,id'
        ]);

        $posts = Post::whereIn('id', $request->posts)->get();

        foreach ($posts as $post) {
            switch ($request->action) {
                case 'publish':
                    $post->update(['status' => 'published', 'published_at' => now()]);
                    break;
                case 'draft':
                    $post->update(['status' => 'draft', 'published_at' => null]);
                    break;
                case 'archive':
                    $post->update(['status' => 'archived']);
                    break;
                case 'featured':
                    $post->update(['featured' => true]);
                    break;
                case 'unfeatured':
                    $post->update(['featured' => false]);
                    break;
                case 'delete':
                    $this->postService->delete($post);
                    break;
            }
        }

        return back()->with('success', 'Bulk action completed successfully!');
    }

    public function duplicate(Post $post)
    {
        $newPost = $post->replicate();
        $newPost->title = $post->title . ' (Copy)';
        $newPost->slug = $post->slug . '-' . Str::random(5);
        $newPost->status = 'draft';
        $newPost->views = 0;
        $newPost->likes_count = 0;
        $newPost->comments_count = 0;
        $newPost->published_at = null;
        $newPost->save();

        // Duplicate categories
        $newPost->categories()->attach($post->categories->pluck('id'));

        // Duplicate tags
        $newPost->tags()->attach($post->tags->pluck('id'));

        // Log activity
        ActivityLog::log('post_duplicated', "Duplicated post: {$post->title} to {$newPost->title}", auth()->id(), [
            'original_post_id' => $post->id,
            'new_post_id' => $newPost->id
        ]);

        return redirect()->route('admin.posts.edit', $newPost)
            ->with('success', 'Post duplicated successfully!');
    }
}
