<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->paginate(20);

        return view('tags.index', compact('tags'));
    }

    public function show($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = Post::published()
            ->whereHas('tags', function($query) use ($tag) {
                $query->where('tags.id', $tag->id);
            })
            ->latest()
            ->paginate(config('app.posts_per_page', 10));

        return view('tags.show', compact('tag', 'posts'));
    }
}
