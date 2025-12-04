<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Models\Follower;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
Route::get('/tags/{slug}', [TagController::class, 'show'])->name('tags.show');

// Search Routes
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/search/autocomplete', [SearchController::class, 'autocomplete'])->name('search.autocomplete');



// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        // Additional Profile Routes
        Route::get('/bookmarks', [ProfileController::class, 'bookmarks'])->name('bookmarks');
        Route::get('/posts', [ProfileController::class, 'posts'])->name('posts');
        Route::get('/followers', [ProfileController::class, 'followers'])->name('followers');
        Route::post('/notifications', [ProfileController::class, 'updateNotificationSettings'])->name('notifications.update');
        Route::get('/{id}', [ProfileController::class, 'show'])->name('show');
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');


    });

    // Comments
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');

    // Bookmarks
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/posts/{post}/bookmark', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');
    Route::delete('/bookmarks/{bookmark}', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');

    // Reports
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');

    // Post Likes (if you want a dedicated route)
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Posts
    Route::resource('posts', AdminPostController::class);
    Route::post('/posts/bulk-action', [AdminPostController::class, 'bulkAction'])->name('posts.bulk-action');
    Route::post('/posts/{post}/duplicate', [AdminPostController::class, 'duplicate'])->name('posts.duplicate');

    // Categories
    Route::resource('categories', AdminCategoryController::class);

    // Users
    Route::resource('users', AdminUserController::class);
    // In admin routes group
    Route::post('/users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::post('/users/bulk-action', [AdminUserController::class, 'bulkAction'])->name('users.bulk-action');

    // Comments
    Route::resource('comments', AdminCommentController::class);
    Route::post('/comments/bulk-action', [AdminCommentController::class, 'bulkAction'])->name('comments.bulk-action');
    Route::post('/comments/{comment}/approve', [AdminCommentController::class, 'approve'])->name('comments.approve');
    Route::post('/comments/{comment}/spam', [AdminCommentController::class, 'markAsSpam'])->name('comments.spam');

    // Reports
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [AdminReportController::class, 'show'])->name('reports.show');
    Route::put('/reports/{report}', [AdminReportController::class, 'update'])->name('reports.update');

    // Settings
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    // Activity Logs
    Route::get('/activity-logs', [AdminController::class, 'activityLogs'])->name('activity-logs.index');
    Route::post('/activity-logs/clear', [AdminController::class, 'clearLogs'])->name('activity-logs.clear');

    // System
    Route::get('/system/info', [AdminController::class, 'systemInfo'])->name('system.info');
    Route::match(['get', 'post'], '/system/maintenance', [AdminController::class, 'maintenance'])->name('system.maintenance');
    Route::match(['get', 'post'], '/system/backup', [AdminController::class, 'backup'])->name('system.backup');



});

// Profile Public Route
Route::get('/profile/{username}/public', [ProfileController::class, 'showPublicProfile'])->name('profile.show.public');

// Include Breeze Auth Routes
require __DIR__ . '/auth.php';
