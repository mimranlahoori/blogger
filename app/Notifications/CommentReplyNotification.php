<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentReplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Comment $comment,
        public Post $post
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Reply to Your Comment')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Someone has replied to your comment on: ' . $this->post->title)
            ->action('View Reply', route('posts.show', $this->post->slug) . '#comment-' . $this->comment->id)
            ->line('Reply: ' . substr($this->comment->content, 0, 100) . '...')
            ->line('Thank you for using our platform!');
    }
}
