<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'email_new_comment', 'email_comment_reply',
        'email_post_like', 'email_new_follower', 'email_newsletter',
        'push_notifications'
    ];

    protected $casts = [
        'email_new_comment' => 'boolean',
        'email_comment_reply' => 'boolean',
        'email_post_like' => 'boolean',
        'email_new_follower' => 'boolean',
        'email_newsletter' => 'boolean',
        'push_notifications' => 'boolean'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
