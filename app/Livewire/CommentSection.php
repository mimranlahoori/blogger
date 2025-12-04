<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Comment;
use Livewire\Component;

class CommentSection extends Component
{
    public $post;
    public $comments;
    public $content = '';
    public $parentId = null;

    protected $rules = [
        'content' => 'required|min:3|max:1000'
    ];

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->loadComments();
    }

    public function loadComments()
    {
        $this->comments = Comment::where('post_id', $this->post->id)
            ->whereNull('parent_id')
            ->approved()
            ->with(['user', 'replies.user', 'replies.replies.user'])
            ->latest()
            ->get();
    }

    public function submitComment()
    {
        $this->validate();

        if (!auth()->check()) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Please login to comment'
            ]);
            return;
        }

        $comment = Comment::create([
            'post_id' => $this->post->id,
            'user_id' => auth()->id(),
            'parent_id' => $this->parentId,
            'content' => $this->content,
            'status' => config('blog.require_comment_approval') ? 'pending' : 'approve',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        $this->content = '';
        $this->parentId = null;

        $this->loadComments();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => config('blog.require_comment_approval')
                ? 'Comment submitted for approval!'
                : 'Comment posted successfully!'
        ]);
    }

    public function setReply($commentId)
    {
        $this->parentId = $commentId;
        $this->dispatch('scroll-to-comment-form');
    }

    public function likeComment($commentId)
    {
        if (!auth()->check()) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Please login to like comments'
            ]);
            return;
        }

        $comment = Comment::find($commentId);

        if ($comment->likes()->where('user_id', auth()->id())->exists()) {
            $comment->likes()->where('user_id', auth()->id())->delete();
            $liked = false;
        } else {
            $comment->likes()->create(['user_id' => auth()->id()]);
            $liked = true;
        }

        $comment->refresh();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $liked ? 'Comment liked!' : 'Like removed!'
        ]);
    }

    public function render()
    {
        return view('livewire.comment-section');
    }
}
