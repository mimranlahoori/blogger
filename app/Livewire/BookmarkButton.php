<?php

namespace App\Livewire;

use App\Models\Post;
use App\Services\PostService;
use Livewire\Component;

class BookmarkButton extends Component
{
    public $post;
    public $isBookmarked;

    protected $postService;

    public function mount(Post $post, PostService $postService)
    {
        $this->post = $post;
        $this->postService = $postService;
        $this->isBookmarked = auth()->check() && $post->bookmarks()->where('user_id', auth()->id())->exists();
    }

    public function toggleBookmark()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $result = $this->postService->toggleBookmark($this->post, auth()->user());
        $this->isBookmarked = $result['bookmarked'];

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $result['message']
        ]);
    }

    public function render()
    {
        return view('livewire.bookmark-button');
    }
}
