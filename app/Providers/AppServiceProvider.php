<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Post;
use App\Policies\PostPolicy;
use App\Models\Comment;
use App\Policies\CommentPolicy;
use App\Models\Category;
use App\Policies\CategoryPolicy;
use App\Services\PostService;
use App\Services\UserService;
use App\Services\CommentService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Services
        $this->app->singleton(PostService::class, function ($app) {
            return new PostService();
        });

        $this->app->singleton(UserService::class, function ($app) {
            return new UserService();
        });

        $this->app->singleton(CommentService::class, function ($app) {
            return new CommentService();
        });

        // Register Repositories (if you have them)
        // $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        // $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Policies
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);

        // Define Additional Gates
        $this->defineGates();

        // Set Default String Length for MySQL
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

        // Set Timezone
        date_default_timezone_set(config('app.timezone', 'UTC'));

    }

    /**
     * Define custom gates for authorization.
     */
    protected function defineGates(): void
    {
        // Admin gate
        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });

        // Moderator gate
        Gate::define('moderator', function ($user) {
            return $user->isModerator();
        });

        // Admin or Moderator gate
        Gate::define('admin-or-moderator', function ($user) {
            return $user->isAdmin() || $user->isModerator();
        });

        // Manage Posts gate
        Gate::define('manage-posts', function ($user) {
            return $user->isAdmin() || $user->isModerator() || $user->id === request()->route('post')->user_id ?? null;
        });

        // Manage Comments gate
        Gate::define('manage-comments', function ($user) {
            return $user->isAdmin() || $user->isModerator() || $user->id === request()->route('comment')->user_id ?? null;
        });

        // Manage Users gate (only admins)
        Gate::define('manage-users', function ($user) {
            return $user->isAdmin();
        });

        // Manage Categories gate (only admins)
        Gate::define('manage-categories', function ($user) {
            return $user->isAdmin();
        });

        // View Dashboard gate
        Gate::define('view-dashboard', function ($user) {
            return $user->isAdmin() || $user->isModerator();
        });

        // Access Settings gate
        Gate::define('access-settings', function ($user) {
            return $user->isAdmin();
        });

        // Approve Comments gate
        Gate::define('approve-comments', function ($user) {
            return $user->isAdmin() || $user->isModerator();
        });

        // Feature Posts gate
        Gate::define('feature-posts', function ($user) {
            return $user->isAdmin();
        });

        // View Reports gate
        Gate::define('view-reports', function ($user) {
            return $user->isAdmin() || $user->isModerator();
        });

        // Manage Reports gate
        Gate::define('manage-reports', function ($user) {
            return $user->isAdmin();
        });
    }

}
