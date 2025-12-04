<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return redirect()->route('home');
        }

        // Search posts
        $posts = Post::published()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                  ->orWhere('content', 'like', '%' . $query . '%')
                  ->orWhere('excerpt', 'like', '%' . $query . '%');
            })
            ->with(['user', 'categories'])
            ->latest()
            ->paginate(10);

        // Search categories
        $categories = Category::where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->withCount('posts')
            ->paginate(10, ['*'], 'categories_page');

        // Search tags
        $tags = Tag::where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->withCount('posts')
            ->paginate(10, ['*'], 'tags_page');

        // Search users
        $users = User::where('name', 'like', '%' . $query . '%')
            ->orWhere('email', 'like', '%' . $query . '%')
            ->orWhere('bio', 'like', '%' . $query . '%')
            ->active()
            ->paginate(10, ['*'], 'users_page');

        return view('search.index', compact('query', 'posts', 'categories', 'tags', 'users'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('query');

        $results = [];

        if ($query) {
            // Posts
            $posts = Post::published()
                ->where('title', 'like', '%' . $query . '%')
                ->select('id', 'title', 'slug')
                ->limit(5)
                ->get()
                ->map(function($post) {
                    return [
                        'type' => 'post',
                        'id' => $post->id,
                        'title' => $post->title,
                        'url' => route('posts.show', $post->slug),
                        'icon' => 'fa-newspaper'
                    ];
                });

            // Categories
            $categories = Category::where('name', 'like', '%' . $query . '%')
                ->select('id', 'name', 'slug')
                ->limit(5)
                ->get()
                ->map(function($category) {
                    return [
                        'type' => 'category',
                        'id' => $category->id,
                        'title' => $category->name,
                        'url' => route('categories.show', $category->slug),
                        'icon' => 'fa-folder'
                    ];
                });

            // Tags
            $tags = Tag::where('name', 'like', '%' . $query . '%')
                ->select('id', 'name', 'slug')
                ->limit(5)
                ->get()
                ->map(function($tag) {
                    return [
                        'type' => 'tag',
                        'id' => $tag->id,
                        'title' => $tag->name,
                        'url' => route('tags.show', $tag->slug),
                        'icon' => 'fa-tag'
                    ];
                });

            $results = $posts->merge($categories)->merge($tags)->take(10);
        }

        return response()->json($results);
    }
}
