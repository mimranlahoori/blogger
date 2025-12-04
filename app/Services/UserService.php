<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function create(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'user',
            'phone' => $data['phone'] ?? null,
            'bio' => $data['bio'] ?? null,
            'website' => $data['website'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'email_verified' => $data['email_verified'] ?? false,
            'email_verified_at' => $data['email_verified'] ? now() : null,
        ]);

        // Create notification settings
        $user->notificationSettings()->create();

        // Log activity
        ActivityLog::log('user_created', "Created user: {$user->name}", null, [
            'user_id' => $user->id,
            'user_name' => $user->name
        ]);

        return $user;
    }

    public function update(User $user, array $data): User
    {
        // Handle password update
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Handle email verification
        if (isset($data['email_verified']) && $data['email_verified'] && !$user->email_verified_at) {
            $data['email_verified_at'] = now();
        }

        // Handle profile picture
        if (isset($data['picture'])) {
            $this->uploadProfilePicture($user, $data['picture']);
            unset($data['picture']);
        }

        $user->update($data);

        // Log activity
        ActivityLog::log('user_updated', "Updated user: {$user->name}", null, [
            'user_id' => $user->id,
            'user_name' => $user->name
        ]);

        return $user;
    }

    public function delete(User $user): bool
    {
        // Delete profile picture if not default
        if ($user->picture && $user->picture !== 'default.png') {
            Storage::delete('public/profile-pictures/' . $user->picture);
        }

        // Log activity before deletion
        ActivityLog::log('user_deleted', "Deleted user: {$user->name}", null, [
            'user_id' => $user->id,
            'user_name' => $user->name
        ]);


        return $user->delete();
    }

    public function toggleActive(User $user): array
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        // Log activity
        ActivityLog::log("user_{$status}", "Status user: {$user->name}", null, [
            'user_id' => $user->id,
            'user_name' => $user->name
        ]);

        return [
            'active' => $user->is_active,
            'message' => "User {$status} successfully"
        ];
    }

    public function updateProfilePicture(User $user, $image): void
    {
        $this->uploadProfilePicture($user, $image);

        // Log activity
        ActivityLog::log('updated_profile_picture', "Updated Profile Picture: {$user->name}", null, [
            'user_id' => $user->id,
            'user_name' => $user->name
        ]);
    }

    public function updateNotificationSettings(User $user, array $data): void
    {
        $settings = $user->notificationSettings ?? $user->notificationSettings()->create();
        $settings->update($data);
    }

    private function uploadProfilePicture(User $user, $image): void
    {
        // Delete old picture if not default
        if ($user->picture && $user->picture !== 'default.png') {
            Storage::delete('public/profile-pictures/' . $user->picture);
        }

        $filename = time() . '_' . $user->id . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('public/profile-pictures', $filename);

        $user->update(['picture' => $filename]);
    }
}
