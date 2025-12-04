<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'picture',
        'role',
        'bio',
        'website',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'email_verified',
        'verification_token',
        'reset_token',
        'reset_expires',
        'last_login',
        'login_attempts',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
        'reset_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login' => 'datetime',
        'reset_expires' => 'datetime',
        'email_verified' => 'boolean',
        'is_active' => 'boolean',
        'login_attempts' => 'integer'
    ];

    protected $attributes = [
        'role' => 'user',
        'picture' => 'default.png',
        'email_verified' => false,
        'login_attempts' => 0,
        'is_active' => true
    ];

    // Relationships
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function postLikes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function commentLikes()
    {
        return $this->hasMany(CommentLike::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function bookmarkedPosts()
    {
        return $this->belongsToMany(Post::class, 'bookmarks')->withTimestamps();
    }

    public function notificationSettings()
    {
        return $this->hasOne(NotificationSetting::class);
    }

    public function followers()
    {
        return $this->hasMany(Follower::class, 'following_id');
    }

    public function following()
    {
        return $this->hasMany(Follower::class, 'follower_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function follow(User $user)
    {
        if (!$this->isFollowing($user) && $this->id !== $user->id) {
            return $this->following()->create(['following_id' => $user->id]);
        }
        return false;
    }

    public function unfollow(User $user)
    {
        return $this->following()->where('following_id', $user->id)->delete();
    }

    // Scopes
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('email_verified', true);
    }

    // Methods
    public function isAdmin()
    {
        return $this->role == 'admin';
    }

    public function isModerator()
    {
        return $this->role == 'moderator';
    }

    public function getProfilePictureAttribute()
    {
        if ($this->picture && $this->picture != 'default.png') {
            return asset('storage/profile-pictures/' . $this->picture);
        }

        return asset('images/default-avatar.png');
    }

    public function getFollowersCountAttribute()
    {
        return $this->followers()->count();
    }


    public function getFollowerCountAttribute()
    {
        return $this->followers()->count();
    }

    public function getFollowingCountAttribute()
    {
        return $this->following()->count();
    }

    public function getPostCountAttribute()
    {
        return $this->posts()->count();
    }

    public function getCommentCountAttribute()
    {
        return $this->comments()->count();
    }

    public function getTotalLikesAttribute()
    {
        return $this->postLikes()->count() + $this->commentLikes()->count();
    }
}
