<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewCommentNotification;
use App\Notifications\CommentReplyNotification;
use Illuminate\Support\Str;

class CommentService
{
    public function create(array $data, Post $post, ?User $user = null): Comment
    {
        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $user ? $user->id : null,
            'parent_id' => $data['parent_id'] ?? null,
            'author_name' => $user ? null : $data['author_name'],
            'author_email' => $user ? null : $data['author_email'],
            'author_website' => $user ? null : ($data['author_website'] ?? null),
            'content' => $data['content'],
            'status' => config('blog.require_comment_approval') ? 'pending' : 'approve',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Send notifications
        $this->sendNotifications($comment, $post, $user);

        // Log activity
        ActivityLog::log(
            'created_comment',
            "Created comment: {$comment->id}",
            auth()->id(),
            [
                'comment_id' => $comment->id,
                'comment_excerpt' => Str::limit($comment->content, 50),
            ]
        );


        return $comment;
    }

    public function update(Comment $comment, array $data): Comment
    {
        $comment->update([
            'content' => $data['content'],
            'is_edited' => true,
            'edited_at' => now(),
        ]);

        // Log activity
        ActivityLog::log(
            'updated_comment',
            "Updated comment: {$comment->id}",
            auth()->id(),
            [
                'comment_id' => $comment->id,
                'comment_excerpt' => Str::limit($comment->content, 50),
            ]
        );

        return $comment;
    }

    public function delete(Comment $comment): bool
    {
        // Log activity before deletion
        ActivityLog::log(
            'deleted_comment',
            "Deleted comment: {$comment->id}",
            auth()->id(),
            [
                'comment_id' => $comment->id,
                'comment_excerpt' => Str::limit($comment->content, 50),
            ]
        );


        return $comment->delete();
    }

    public function approve(Comment $comment): Comment
    {
        $comment->update(['status' => 'approve']);

        // Log activity
        ActivityLog::log(
            'approved_comment',
            "Approved comment: {$comment->id}",
            auth()->id(),
            [
                'comment_id' => $comment->id,
                'comment_excerpt' => Str::limit($comment->content, 50),
            ]
        );


        return $comment;
    }

    public function markAsSpam(Comment $comment): Comment
    {
        $comment->update(['status' => 'spam']);

        // Log activity
        ActivityLog::log(
            'marked_comment_as_spam',
            "Marked comment as spam: {$comment->id}",
            auth()->id(),
            [
                'comment_id' => $comment->id,
                'comment_excerpt' => Str::limit($comment->content, 50),
            ]
        );


        return $comment;
    }

    public function toggleLike(Comment $comment, User $user): array
    {
        $like = $comment->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $comment->likes()->create(['user_id' => $user->id]);
            $liked = true;

            // Log activity
            ActivityLog::log(
                'liked_comment',
                "Liked comment: {$comment->id}",
                auth()->id(),
                [
                    'comment_id' => $comment->id,
                    'comment_excerpt' => Str::limit($comment->content, 50),
                ]
            );

        }

        $likesCount = $comment->likes()->count();
        $comment->update(['likes_count' => $likesCount]);

        return [
            'liked' => $liked,
            'likes_count' => $likesCount
        ];
    }

    public function report(Comment $comment, User $reporter, string $reason, ?string $description = null): void
    {
        $comment->reports()->create([
            'reporter_id' => $reporter->id,
            'reason' => $reason,
            'description' => $description,
        ]);

        $comment->increment('reported_count');

        // Log activity
        ActivityLog::log(
            'reported_comment',
            "Reported comment: {$comment->id}",
            auth()->id(),
            [
                'comment_id' => $comment->id,
                'comment_excerpt' => Str::limit($comment->content, 50),
            ]
        );

    }

    private function sendNotifications(Comment $comment, Post $post, ?User $user = null): void
    {
        // Notify post author if different from commenter and wants notifications
        if ($post->user && (!$user || $post->user->id !== $user->id)) {
            $settings = $post->user->notificationSettings;
            if ($settings && $settings->email_new_comment) {
                $post->user->notify(new NewCommentNotification($comment, $post));
            }
        }

        // Notify parent comment author if this is a reply
        if ($comment->parent_id && $comment->parent->user) {
            $parentCommentAuthor = $comment->parent->user;
            if (!$user || $parentCommentAuthor->id !== $user->id) {
                $settings = $parentCommentAuthor->notificationSettings;
                if ($settings && $settings->email_comment_reply) {
                    $parentCommentAuthor->notify(new CommentReplyNotification($comment, $post));
                }
            }
        }
    }
}
