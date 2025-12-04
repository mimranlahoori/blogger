<?php

namespace App\Providers;

use App\Events\CommentCreated;
use App\Events\PostCreated;
use App\Events\PostLiked;
use App\Events\UserRegistered;
use App\Listeners\SendCommentNotifications;
use App\Listeners\SendNewPostNotification;
use App\Listeners\SendPostLikeNotification;
use App\Listeners\SendWelcomeEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // UserRegistered::class => [
        //     SendWelcomeEmail::class,
        // ],

        // PostCreated::class => [
        //     SendNewPostNotification::class,
        // ],

        // CommentCreated::class => [
        //     SendCommentNotifications::class,
        // ],

        // PostLiked::class => [
        //     SendPostLikeNotification::class,
        // ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
