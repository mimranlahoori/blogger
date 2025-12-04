<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Request $request)
    {
        $query = Comment::with(['post', 'user', 'replies']);

        // Filter by post
        if ($request->has('post_id')) {
            $query->where('post_id', $request->post_id);
        }

        // Filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            // Only show approved comments by default for guests
            if (!Auth::check() || !Auth::user()->isAdmin()) {
                $query->where('status', 'approve');
            }
        }

        $comments = $query->latest()->paginate(20);

        if ($request->ajax()) {
            return response()->json($comments);
        }

        return view('comments.index', compact('comments'));
    }

    public function show(Comment $comment)
    {
        $comment->load(['post', 'user', 'replies.user', 'replies.replies']);

        return view('comments.show', compact('comment'));
    }

    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|min:3|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        // Check if parent comment belongs to the same post
        if ($request->parent_id) {
            $parentComment = Comment::findOrFail($request->parent_id);
            if ($parentComment->post_id !== $post->id) {
                return response()->json(['error' => 'Invalid parent comment'], 422);
            }
        }

        $comment = $this->commentService->create(
            [
                'content' => $request->content,
                'parent_id' => $request->parent_id,
                'author_name' => !Auth::check() ? $request->input('author_name', 'Anonymous') : null,
                'author_email' => !Auth::check() ? $request->input('author_email') : null,
                'author_website' => !Auth::check() ? $request->input('author_website') : null,
            ],
            $post,
            Auth::user()
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => config('blog.require_comment_approval')
                    ? 'Comment submitted for approval!'
                    : 'Comment posted successfully!',
                'comment' => $comment->load('user')
            ]);
        }

        return back()->with('success',
            config('blog.require_comment_approval')
                ? 'Comment submitted for approval!'
                : 'Comment posted successfully!'
        );
    }

    public function edit(Comment $comment)
    {
        $this->authorize('update', $comment);

        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate([
            'content' => 'required|string|min:3|max:1000'
        ]);

        $comment = $this->commentService->update($comment, [
            'content' => $request->content
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment updated successfully!',
                'comment' => $comment
            ]);
        }

        return back()->with('success', 'Comment updated successfully!');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $this->commentService->delete($comment);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully!'
            ]);
        }

        return back()->with('success', 'Comment deleted successfully!');
    }

    public function like(Comment $comment)
    {
        if (!Auth::check()) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Please login to like comments'], 401);
            }
            return redirect()->route('login');
        }

        $result = $this->commentService->toggleLike($comment, Auth::user());

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'liked' => $result['liked'],
                'likes_count' => $result['likes_count'],
                'message' => $result['liked'] ? 'Comment liked!' : 'Like removed!'
            ]);
        }

        return back()->with('success', $result['liked'] ? 'Comment liked!' : 'Like removed!');
    }

    public function report(Request $request, Comment $comment)
    {
        $request->validate([
            'reason' => 'required|in:spam,harassment,inappropriate,copyright,other',
            'description' => 'required_if:reason,other|nullable|string|max:500'
        ]);

        if (!Auth::check()) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Please login to report comments'], 401);
            }
            return redirect()->route('login');
        }

        // Check if user already reported this comment
        $existingReport = $comment->reports()
            ->where('reporter_id', Auth::id())
            ->first();

        if ($existingReport) {
            if (request()->ajax()) {
                return response()->json([
                    'error' => 'You have already reported this comment'
                ], 422);
            }
            return back()->with('error', 'You have already reported this comment');
        }

        $this->commentService->report(
            $comment,
            Auth::user(),
            $request->reason,
            $request->description
        );

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment reported successfully!'
            ]);
        }

        return back()->with('success', 'Comment reported successfully!');
    }

    public function replies(Comment $comment)
    {
        $replies = $comment->replies()
            ->with('user')
            ->approved()
            ->latest()
            ->paginate(10);

        if (request()->ajax()) {
            return response()->json($replies);
        }

        return view('comments.replies', compact('comment', 'replies'));
    }

    public function userComments(Request $request)
    {
        $user = Auth::user();

        $query = $user->comments()->with('post');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $comments = $query->latest()->paginate(20);

        return view('comments.user-index', compact('comments'));
    }
}
