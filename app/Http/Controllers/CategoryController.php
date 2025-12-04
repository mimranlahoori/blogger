<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('posts')
            ->active()
            ->mainCategories()
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->active()
            ->with(['posts' => function($query) {
                $query->published()->latest();
            }])
            ->firstOrFail();

        $subcategories = Category::where('parent_id', $category->id)
            ->active()
            ->withCount('posts')
            ->get();

        $posts = $category->posts()->paginate(config('app.posts_per_page', 10));

        return view('categories.show', compact('category', 'subcategories', 'posts'));
    }
}
