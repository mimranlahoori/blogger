<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\User;
use Livewire\Component;

class FollowButton extends Component
{
    public $user;
    public $isFollowing;
    public $followersCount;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->isFollowing = auth()->check() && auth()->user()->isFollowing($user);
        $this->followersCount = $user->followers_count;
    }

    public function toggleFollow()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->id() === $this->user->id) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'You cannot follow yourself!'
            ]);
            return;
        }

        if ($this->isFollowing) {
            auth()->user()->unfollow($this->user);
            $this->isFollowing = false;
            $this->followersCount--;

            // Log activity
            ActivityLog::log('unfollowed_user', "Unfollowed {$this->user->name}", auth()->id());

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Unfollowed successfully!'
            ]);
        } else {
            auth()->user()->follow($this->user);
            $this->isFollowing = true;
            $this->followersCount++;

            // Log activity
            ActivityLog::log('followed_user', "Followed {$this->user->name}", auth()->id());

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Followed successfully!'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.follow-button');
    }
}
