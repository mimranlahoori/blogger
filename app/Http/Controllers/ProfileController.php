<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Bookmark;
use App\Models\Follower;
use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request, $username)
    {
        $user = User::where('name', $username)
            ->orWhere('id', $username)
            ->withCount(['posts', 'followers', 'following'])
            ->firstOrFail();

        $isFollowing = Auth::check() ? Auth::user()->isFollowing($user) : false;

        $posts = Post::where('user_id', $user->id)
            ->published()
            ->with(['categories', 'user'])
            ->latest()
            ->paginate(10);

        return view('profile.show', compact('user', 'posts', 'isFollowing'));
    }
    public function edit()
    {
        $user = Auth::user();
        $notificationSettings = $user->notificationSettings ?? new NotificationSetting();

        return view('profile.edit', compact('user', 'notificationSettings'));
    }

    public function update(Request $request, User $user)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed'
        ]);

        // Update basic info
        $user->update($request->only([
            'name',
            'email',
            'phone',
            'bio',
            'website',
            'facebook_url',
            'twitter_url',
            'instagram_url'
        ]));

        // Handle profile picture upload
        if ($request->hasFile('picture')) {

            if ($user->picture && $user->picture !== 'default.png') {
                Storage::disk('public')->delete('profile-pictures/' . $user->picture);
            }

            $filename = time() . '_' . $request->file('picture')->getClientOriginalName();

            $request->file('picture')
                ->storeAs('profile-pictures', $filename, 'public');

            $user->picture = $filename;
            $user->save();
        }


        // Update password if provided
        if ($request->filled('current_password') && $request->filled('password')) {
            if (Hash::check($request->current_password, $user->password)) {
                $user->update(['password' => Hash::make($request->password)]);
            } else {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    public function bookmarks()
    {
        $user = auth()->user();

        $bookmarks = Bookmark::where('user_id', $user->id)
            ->with(['post.user', 'post.categories'])
            ->latest()
            ->paginate(12);

        return view('profile.bookmarks', compact('bookmarks'));
    }

    public function posts()
    {
        $posts = Post::where('user_id', Auth::id())
            ->with('categories', 'user')
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => Post::where('user_id', Auth::id())->count(),
            'published' => Post::where('user_id', Auth::id())->where('status', 'published')->count(),
            'drafts' => Post::where('user_id', Auth::id())->where('status', 'draft')->count(),
            'featured' => Post::where('user_id', Auth::id())->where('featured', true)->count(),
        ];

        return view('profile.posts', compact('posts', 'stats'));
    }

    public function followers()
    {
        $user = auth()->user();

        $followers = Follower::where('following_id', $user->id)
            ->with('follower')
            ->latest()
            ->paginate(20);

        $following = Follower::where('follower_id', $user->id)
            ->with('following')
            ->latest()
            ->paginate(20);

        return view('profile.followers', compact('followers', 'following'));
    }

    public function updateNotificationSettings(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'email_new_comment' => 'boolean',
            'email_comment_reply' => 'boolean',
            'email_post_like' => 'boolean',
            'email_new_follower' => 'boolean',
            'email_newsletter' => 'boolean',
            'push_notifications' => 'boolean'
        ]);

        $settings = $user->notificationSettings ?? new NotificationSetting();
        $settings->user_id = $user->id;
        $settings->fill($request->only([
            'email_new_comment',
            'email_comment_reply',
            'email_post_like',
            'email_new_follower',
            'email_newsletter',
            'push_notifications'
        ]));
        $settings->save();

        return back()->with('success', 'Notification settings updated successfully!');
    }

    public function showPublicProfile($username)
    {
        $user = User::where('name', $username)
            ->orWhere('id', $username)
            ->firstOrFail();

        $posts = Post::published()
            ->where('user_id', $user->id)
            ->with(['categories', 'user'])
            ->latest()
            ->paginate(10);

        $isFollowing = Auth::check() && Follower::where([
            'follower_id' => Auth::id(),
            'following_id' => $user->id
        ])->exists();

        // Get follower/following counts
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        // Get user stats
        $stats = [
            'total_posts' => $posts->total(),
            'total_comments' => $user->comments()->count(),
            'total_likes' => $user->postLikes()->count() + $user->commentLikes()->count(),
        ];

        return view('profile.public', compact('user', 'posts', 'isFollowing', 'followersCount', 'followingCount', 'stats'));
    }

    public function toggleFollow(Request $request, $userId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login to follow users'], 401);
        }

        $userToFollow = User::findOrFail($userId);
        $currentUser = Auth::user();

        if ($currentUser->id === $userToFollow->id) {
            return response()->json(['error' => 'You cannot follow yourself'], 400);
        }

        if ($currentUser->isFollowing($userToFollow)) {
            $currentUser->unfollow($userToFollow);
            $isFollowing = false;
            $message = 'Unfollowed successfully';
        } else {
            $currentUser->follow($userToFollow);
            $isFollowing = true;
            $message = 'Followed successfully';
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'isFollowing' => $isFollowing,
                'followersCount' => $userToFollow->followers()->count(),
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }
}
