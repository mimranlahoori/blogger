<!-- resources/views/admin/dashboard/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <i class="fas fa-newspaper text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Posts</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_posts'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_users'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <i class="fas fa-comments text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Comments</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ $stats['total_comments'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                            <i class="fas fa-flag text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Reports</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ $stats['pending_reports'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Content -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Posts -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Posts</h3>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($recentPosts as $post)
                            <div class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <a href="{{ route('admin.posts.show', $post) }}"
                                            class="text-sm font-medium text-gray-900 dark:text-white hover:text-blue-600">
                                            {{ $post->title }}
                                        </a>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $post->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $post->status === 'published'
                                            ? 'bg-green-100 text-green-800'
                                            : ($post->status === 'draft'
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Users -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Users</h3>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($recentUsers as $user)
                            <div class="px-6 py-4">
                                <div class="flex items-center">
                                    <img class="h-10 w-10 rounded-full" src="{{ $user->profile_picture }}"
                                        alt="{{ $user->name }}">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $user->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                    </div>
                                    <div class="ml-auto">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $user->role === 'admin'
                                                ? 'bg-red-100 text-red-800'
                                                : ($user->role === 'moderator'
                                                    ? 'bg-blue-100 text-blue-800'
                                                    : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Add this section to your dashboard -->
            <div class="col-span-2 py-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Activities</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($recentActivities as $activity)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        @if ($activity->user)
                                            <img src="{{ $activity->user->profile_picture }}"
                                                alt="{{ $activity->user->name }}" class="w-8 h-8 rounded-full">
                                        @else
                                            <div
                                                class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                <i class="fas fa-server text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm">
                                            <span class="font-medium text-gray-900 dark:text-white">
                                                {{ $activity->user ? $activity->user->name : 'System' }}
                                            </span>
                                            <span
                                                class="text-gray-600 dark:text-gray-400">{{ $activity->description }}</span>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $activity->created_at->diffForHumans() }}
                                            <span class="mx-1">•</span>
                                            <span
                                                class="capitalize">{{ str_replace('_', ' ', $activity->action) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">No recent activities</p>
                            @endforelse
                        </div>

                        @if ($recentActivities->count() > 0)
                            <div class="mt-4 text-center">
                                <a href="{{ route('admin.activity-logs.index') }}"
                                    class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                    View all activities
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
