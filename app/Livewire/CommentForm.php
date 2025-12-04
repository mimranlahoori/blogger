<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\Post;
use App\Models\Comment;
use Livewire\Component;

class CommentForm extends Component
{
    public $post;
    public $content;
    public $parentId = null;

    protected $rules = [
        'content' => 'required|min:3|max:1000'
    ];

    public function mount(Post $post, $parentId = null)
    {
        $this->post = $post;
        $this->parentId = $parentId;
    }

    public function submitComment()
    {
        $this->validate();

        if (!auth()->check()) {
            session()->flash('error', 'Please login to comment');
            return;
        }

        $comment = Comment::create([
            'post_id' => $this->post->id,
            'user_id' => auth()->id(),
            'parent_id' => $this->parentId,
            'content' => $this->content,
            'status' => 'pending', // Or 'approved' based on your settings
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        $this->content = '';

        // Emit event to refresh comments
        $this->emit('commentAdded', $comment->id);

        // Log activity
        ActivityLog::log('comment_added', "Commented on post: {$this->post->title}", auth()->id());

        session()->flash('success', 'Comment submitted successfully!');
    }

    public function render()
    {
        return view('livewire.comment-form');
    }
}
