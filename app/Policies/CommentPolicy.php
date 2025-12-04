<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Comment $comment): bool
    {
        return $comment->status == 'approved' ||
               $user->id == $comment->user_id ||
               $user->isAdmin() ||
               $user->isModerator();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->is_active;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, Comment $comment): bool
    {
        return $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can mark as spam.
     */
    public function markSpam(User $user, Comment $comment): bool
    {
        return $user->isAdmin() || $user->isModerator();
    }
}
