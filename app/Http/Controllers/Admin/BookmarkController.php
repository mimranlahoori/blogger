<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        $bookmarks = Bookmark::where('user_id', Auth::id())
            ->with('post.user', 'post.categories')
            ->latest()
            ->paginate(10);

        return view('bookmarks.index', compact('bookmarks'));
    }

    public function toggle(Post $post)
    {
        $result = $this->postService->toggleBookmark($post, Auth::user());

        if (request()->ajax()) {
            return response()->json($result);
        }

        return back()->with('success', $result['message']);
    }

    public function destroy(Bookmark $bookmark)
    {
        $this->authorize('delete', $bookmark);

        $bookmark->delete();

        return back()->with('success', 'Bookmark removed successfully!');
    }
}
