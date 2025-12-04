<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('posts')->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->get();

        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active', true)
        ]);

        if ($request->hasFile('image')) {
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/categories', $filename);
            $category->image = $filename;
            $category->save();
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function edit(Category $category)
    {
        $categories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->get();

        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => $request->name !== $category->name ? Str::slug($request->name) : $category->slug,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'sort_order' => $request->sort_order ?? $category->sort_order,
            'is_active' => $request->boolean('is_active', $category->is_active)
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                \Storage::delete('public/categories/' . $category->image);
            }

            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/categories', $filename);
            $category->image = $filename;
            $category->save();
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        // Check if category has posts
        if ($category->posts()->count() > 0) {
            return back()->with('error', 'Cannot delete category that has posts!');
        }

        // Delete image if exists
        if ($category->image) {
            \Storage::delete('public/categories/' . $category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}
