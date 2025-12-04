<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Report;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_posts' => Post::count(),
            'published_posts' => Post::published()->count(),
            'total_users' => User::count(),
            'new_users' => User::whereDate('created_at', today())->count(),
            'total_comments' => Comment::count(),
            'pending_comments' => Comment::pending()->count(),
            'pending_reports' => Report::pending()->count(),
        ];

        $recentPosts = Post::latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();
        $recentComments = Comment::with(['post', 'user'])->latest()->take(5)->get();

        return view('admin.dashboard.index', compact('stats', 'recentPosts', 'recentUsers', 'recentComments'));
    }
}
