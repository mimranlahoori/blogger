<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->id == $model->id || $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $user->id == $model->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Cannot delete yourself
        if ($user->id == $model->id) {
            return false;
        }

        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can change user roles.
     */
    public function changeRole(User $user, User $model): bool
    {
        // Cannot change your own role and cannot change other admin's roles
        if ($user->id == $model->id || $model->isAdmin()) {
            return false;
        }

        return $user->isAdmin();
    }

    /**
     * Determine whether the user can activate/deactivate users.
     */
    public function toggleActive(User $user, User $model): bool
    {
        // Cannot deactivate yourself
        if ($user->id == $model->id) {
            return false;
        }

        return $user->isAdmin();
    }
    public function bulkAction(User $user): bool
    {
        return $user->isAdmin();
    }
    public function manageNotifications(User $user, User $model): bool
    {
        return $user->id == $model->id || $user->isAdmin();
    }
    public function viewFollowers(User $user, User $model): bool
    {
        return $user->id == $model->id || $user->isAdmin() || $user->isModerator();
    }
    public function viewBookmarks(User $user, User $model): bool
    {
        return $user->id == $model->id || $user->isAdmin() || $user->isModerator();
    }
    public function viewPosts(User $user, User $model): bool
    {
        return $user->id == $model->id || $user->isAdmin() || $user->isModerator();
    }
    public function updateProfile(User $user, User $model): bool
    {
        return $user->id == $model->id;
    }
    public function uploadProfilePicture(User $user, User $model): bool
    {
        return $user->id == $model->id;
    }
    public function deleteProfilePicture(User $user, User $model): bool
    {
        return $user->id == $model->id;
    }
    public function viewPublicProfile(User $user, User $model): bool
    {
        return true;
    }
    public function updateNotificationSettings(User $user, User $model): bool
    {
        return $user->id == $model->id;
    }
    public function viewAdminPanel(User $user): bool
    {
        return $user->isAdmin() || $user->isModerator();
    }
    public function manageUsers(User $user): bool
    {
        return $user->isAdmin();
    }
    public function manageRoles(User $user): bool
    {
        return $user->isAdmin();
    }
    public function manageSiteSettings(User $user): bool
    {
        return $user->isAdmin();
    }
    public function manageCategories(User $user): bool
    {
        return $user->isAdmin() || $user->isModerator();
    }

}
