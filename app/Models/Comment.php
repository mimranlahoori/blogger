<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id', 'user_id', 'parent_id', 'author_name', 'author_email',
        'author_website', 'content', 'status', 'ip_address', 'user_agent',
        'likes_count', 'reported_count', 'is_edited', 'edited_at'
    ];

    protected $casts = [
        'is_edited' => 'boolean',
        'edited_at' => 'datetime',
        'likes_count' => 'integer',
        'reported_count' => 'integer'
    ];

    // Relationships
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->orderBy('created_at');
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approve');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Methods
    public function getAuthorNameAttribute()
    {
        return $this->user ? $this->user->name : $this->attributes['author_name'];
    }

    public function getAuthorAvatarAttribute()
    {
        if ($this->user) {
            return $this->user->profile_picture;
        }
        return asset('images/default-avatar.png');
    }

    public function isReply()
    {
        return !is_null($this->parent_id);
    }


}
