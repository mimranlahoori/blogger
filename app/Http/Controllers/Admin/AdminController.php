<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Report;
use App\Models\ActivityLog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Get statistics
        $stats = [
            'total_posts' => Post::count(),
            'published_posts' => Post::published()->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
            'total_users' => User::count(),
            'new_users' => User::whereDate('created_at', today())->count(),
            'active_users' => User::active()->count(),
            'total_comments' => Comment::count(),
            'pending_comments' => Comment::pending()->count(),
            'pending_reports' => Report::pending()->count(),
            'total_categories' => Category::count(),
        ];

        // Get recent activities
        $recentPosts = Post::with('user')->latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();
        $recentComments = Comment::with(['post', 'user'])->latest()->take(5)->get();

        // Get popular posts
        $popularPosts = Post::published()->orderBy('views', 'desc')->take(5)->get();

        // Get activity logs
        $recentActivities = ActivityLog::with('user')->latest()->take(10)->get();

        // Get posts by status chart data
        $postsByStatus = Post::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => $item->count];
            });

        // Get users by role chart data
        $usersByRole = User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->role => $item->count];
            });

        return view('admin.dashboard.index', compact(
            'stats',
            'recentPosts',
            'recentUsers',
            'recentComments',
            'popularPosts',
            'recentActivities',
            'postsByStatus',
            'usersByRole'
        ));
    }

    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user');

        // Filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->has('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // Search
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('action', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $logs = $query->latest()->paginate(20);
        $users = User::all();

        return view('admin.activity-logs.index', compact('logs', 'users'));
    }

    public function clearLogs(Request $request)
    {
        $validated = $request->validate([
            'older_than' => 'nullable|integer|min:1'
        ]);

        $query = ActivityLog::query();

        if ($request->has('older_than')) {
            $date = now()->subDays($request->older_than);
            $query->where('created_at', '<', $date);
        }

        $count = $query->count();
        $query->delete();

        // Log this action
        ActivityLog::log('cleared_logs', "Cleared {$count} activity logs", auth()->id());

        return back()->with('success', "Successfully cleared {$count} activity logs!");
    }

    public function systemInfo()
    {
        $info = [
            'laravel_version' => app()->version(),
            'php_version' => phpversion(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'server_os' => php_uname(),
            'database_driver' => config('database.default'),
            'timezone' => config('app.timezone'),
            'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
            'environment' => app()->environment(),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
        ];

        return view('admin.system.info', compact('info'));
    }

    public function maintenance(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'action' => 'required|in:clear_cache,clear_views,clear_compiled,optimize,storage_link'
            ]);

            $action = $request->action;
            $output = [];
            $success = true;

            switch ($action) {
                case 'clear_cache':
                    \Artisan::call('cache:clear');
                    $output[] = 'Application cache cleared!';
                    break;

                case 'clear_views':
                    \Artisan::call('view:clear');
                    $output[] = 'Compiled views cleared!';
                    break;

                case 'clear_compiled':
                    \Artisan::call('clear-compiled');
                    $output[] = 'Compiled services and packages cleared!';
                    break;

                case 'optimize':
                    \Artisan::call('optimize:clear');
                    $output[] = 'Optimization cleared!';
                    break;

                case 'storage_link':
                    \Artisan::call('storage:link');
                    $output[] = 'Storage link created!';
                    break;
            }

            // Log this action
            ActivityLog::log('maintenance', "Performed maintenance: {$action}", auth()->id());

            return back()->with('success', implode(' ', $output));
        }

        return view('admin.system.maintenance');
    }

    public function backup(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'type' => 'required|in:database,full'
            ]);

            try {
                // Note: In production, you should use spatie/laravel-backup package
                // This is a simplified example

                if ($request->type === 'database') {
                    \Artisan::call('database:backup');
                    $message = 'Database backup created successfully!';
                } else {
                    \Artisan::call('backup:run');
                    $message = 'Full backup created successfully!';
                }

                // Log this action
                ActivityLog::log('created_backup', "Created {$request->type} backup", auth()->id());

                return back()->with('success', $message);

            } catch (\Exception $e) {
                return back()->with('error', 'Backup failed: ' . $e->getMessage());
            }
        }

        return view('admin.system.backup');
    }
}
