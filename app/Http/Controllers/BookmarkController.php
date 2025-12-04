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

    /**
     * Display user's bookmarks
     */
    public function index()
    {
        $bookmarks = Bookmark::where('user_id', Auth::id())
            ->with('post.user', 'post.categories')
            ->latest()
            ->paginate(10);

        return view('bookmarks.index', compact('bookmarks'));
    }

    /**
     * Toggle bookmark for a post
     */
    public function toggle(Post $post)
    {
        if (!Auth::check()) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to bookmark posts'
                ], 401);
            }
            return redirect()->route('login');
        }

        $result = $this->postService->toggleBookmark($post, Auth::user());

        if (request()->ajax()) {
            return response()->json($result);
        }

        return back()->with('success', $result['message']);
    }

    /**
     * Remove a bookmark
     */
    public function destroy(Bookmark $bookmark)
    {
        // Authorization - user can only delete their own bookmarks
        if ($bookmark->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $bookmark->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Bookmark removed successfully!'
            ]);
        }

        return back()->with('success', 'Bookmark removed successfully!');
    }

    /**
     * Store a new bookmark
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500'
        ]);

        $bookmark = Bookmark::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'post_id' => $post->id
            ],
            [
                'notes' => $request->notes
            ]
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Post bookmarked successfully!',
                'bookmark' => $bookmark
            ]);
        }

        return back()->with('success', 'Post bookmarked successfully!');
    }
}
