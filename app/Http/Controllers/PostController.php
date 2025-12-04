<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::published()->with(['user', 'categories'])->latest();

        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->firstOrFail();
            $query->whereHas('categories', function ($q) use ($category) {
                $q->where('categories.id', $category->id);
            });
        }

        if ($request->has('tag')) {
            $tag = Tag::where('slug', $request->tag)->firstOrFail();
            $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('tags.id', $tag->id);
            });
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $posts = $query->paginate(config('app.posts_per_page', 10));

        $featuredPosts = Post::published()->featured()->latest()->take(3)->get();
        $categories = Category::active()->withCount('posts')->get();
        $popularTags = Tag::popular(10)->get();

        return view('posts.index', compact('posts', 'featuredPosts', 'categories', 'popularTags'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->published()
            ->with([
                'user',
                'categories',
                'tags',
                'comments' => function ($query) {
                    $query->approved()
                        ->whereNull('parent_id')
                        ->with([
                            'user',
                            'replies' => function ($q) {
                                $q->approved()->with('user');
                            }
                        ]);
                }
            ])
            ->firstOrFail();

        // Increment views
        $post->incrementViews();

        // Related posts
        $relatedPosts = Post::published()
            ->whereHas('categories', function ($query) use ($post) {
                $query->whereIn('categories.id', $post->categories->pluck('id'));
            })
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(3)
            ->get();

        return view('posts.show', compact('post', 'relatedPosts'));
    }
}
