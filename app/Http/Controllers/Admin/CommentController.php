<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Request $request)
    {
        $query = Comment::with(['post', 'user']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by post
        if ($request->has('post_id')) {
            $query->where('post_id', $request->post_id);
        }

        // Search
        if ($request->has('search')) {
            $query->where('content', 'like', '%' . $request->search . '%')
                  ->orWhereHas('post', function($q) use ($request) {
                      $q->where('title', 'like', '%' . $request->search . '%');
                  });
        }

        $comments = $query->latest()->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    public function show(Comment $comment)
    {
        return view('admin.comments.show', compact('comment'));
    }

    public function edit(Comment $comment)
    {
        return view('admin.comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'status' => 'required|in:pending,approve,spam,trash,delete'
        ]);

        $this->commentService->update($comment, [
            'content' => $request->content,
            'status' => $request->status
        ]);

        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment updated successfully!');
    }

    public function destroy(Comment $comment)
    {
        $this->commentService->delete($comment);

        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment deleted successfully!');
    }

    public function approve(Comment $comment)
    {
        $this->commentService->approve($comment);

        return back()->with('success', 'Comment approved successfully!');
    }

    public function markAsSpam(Comment $comment)
    {
        $this->commentService->markAsSpam($comment);

        return back()->with('success', 'Comment marked as spam!');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,spam,trash,delete',
            'comments' => 'required|array',
            'comments.*' => 'exists:comments,id'
        ]);

        $comments = Comment::whereIn('id', $request->comments)->get();

        foreach ($comments as $comment) {
            switch ($request->action) {
                case 'approve':
                    $this->commentService->approve($comment);
                    break;
                case 'spam':
                    $this->commentService->markAsSpam($comment);
                    break;
                case 'trash':
                    $comment->update(['status' => 'trash']);
                    break;
                case 'delete':
                    $this->commentService->delete($comment);
                    break;
            }
        }

        return back()->with('success', 'Bulk action completed successfully!');
    }
}
