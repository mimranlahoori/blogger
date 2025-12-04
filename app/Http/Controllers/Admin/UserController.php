<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin,moderator',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'email_verified' => 'boolean'
        ]);

        $user = $this->userService->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'website' => $request->website,
            'facebook_url' => $request->facebook_url,
            'twitter_url' => $request->twitter_url,
            'instagram_url' => $request->instagram_url,
            'is_active' => $request->boolean('is_active'),
            'email_verified' => $request->boolean('email_verified')
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        $user->load(['posts', 'comments', 'followers', 'following']);

        $stats = [
            'total_posts' => $user->posts()->count(),
            'total_comments' => $user->comments()->count(),
            'followers_count' => $user->followers()->count(),
            'following_count' => $user->following()->count(),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:user,admin,moderator',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'email_verified' => 'boolean'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'website' => $request->website,
            'facebook_url' => $request->facebook_url,
            'twitter_url' => $request->twitter_url,
            'instagram_url' => $request->instagram_url,
            'is_active' => $request->boolean('is_active'),
            'email_verified' => $request->boolean('email_verified')
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        if ($request->hasFile('picture')) {
            $data['picture'] = $request->file('picture');
        }

        $this->userService->update($user, $data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $this->userService->delete($user);

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account!');
        }

        $result = $this->userService->toggleActive($user);

        return back()->with('success', $result['message']);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'users' => 'required|array',
            'users.*' => 'exists:users,id'
        ]);

        $users = User::whereIn('id', $request->users);

        // Prevent deleting yourself
        if ($request->action === 'delete') {
            $users = $users->where('id', '!=', auth()->id());
        }

        foreach ($users->get() as $user) {
            switch ($request->action) {
                case 'activate':
                    $user->update(['is_active' => true]);
                    break;
                case 'deactivate':
                    if ($user->id !== auth()->id()) {
                        $user->update(['is_active' => false]);
                    }
                    break;
                case 'delete':
                    if ($user->id !== auth()->id()) {
                        $user->delete();
                    }
                    break;
            }
        }

        return back()->with('success', 'Bulk action completed successfully!');
    }
}
