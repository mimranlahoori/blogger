<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\Post;
use Livewire\Component;

class LikeButton extends Component
{
    public $post;
    public $isLiked;
    public $likesCount;

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->isLiked = auth()->check() && $post->likes()->where('user_id', auth()->id())->exists();
        $this->likesCount = $post->likes_count;
    }

    public function toggleLike()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if ($this->isLiked) {
            $this->post->likes()->where('user_id', auth()->id())->delete();
            $this->isLiked = false;
            $this->likesCount--;
        } else {
            $this->post->likes()->create(['user_id' => auth()->id()]);
            $this->isLiked = true;
            $this->likesCount++;

            // Log activity
            ActivityLog::log('post_like', "Liked post: {$this->post->title}", auth()->id());
        }
    }

    public function render()
    {
        return view('livewire.like-button');
    }
}
