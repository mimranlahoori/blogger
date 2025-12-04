<!-- resources/views/profile/followers.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Followers & Following') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage your connections</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Followers</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ auth()->user()->followers()->count() }}</p>
                        </div>
                        <div class="bg-blue-500 rounded-lg p-3">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Following</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ auth()->user()->following()->count() }}</p>
                        </div>
                        <div class="bg-green-500 rounded-lg p-3">
                            <i class="fas fa-user-friends text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex">
                        <button id="followers-tab"
                                class="py-4 px-6 text-sm font-medium border-b-2 border-blue-500 text-blue-600 dark:text-blue-400 flex-1 text-center">
                            <i class="fas fa-users mr-2"></i> Followers ({{ $followers->total() }})
                        </button>
                        <button id="following-tab"
                                class="py-4 px-6 text-sm font-medium border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 flex-1 text-center">
                            <i class="fas fa-user-friends mr-2"></i> Following ({{ $following->total() }})
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Followers Tab Content -->
            <div id="followers-content" class="tab-content">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">People Following You</h3>
                    </div>

                    <!-- Followers List -->
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($followers as $follower)
                            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <!-- User Avatar -->
                                        <img src="{{ $follower->follower->profile_picture }}"
                                             alt="{{ $follower->follower->name }}"
                                             class="w-12 h-12 rounded-full mr-4">

                                        <!-- User Info -->
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white">
                                                <a href="{{ route('profile.show.public', $follower->follower->name) }}"
                                                   class="hover:text-blue-600 dark:hover:text-blue-400">
                                                    {{ $follower->follower->name }}
                                                </a>
                                            </h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                @if($follower->follower->bio)
                                                    {{ Str::limit($follower->follower->bio, 60) }}
                                                @else
                                                    Member since {{ $follower->follower->created_at->format('M Y') }}
                                                @endif
                                            </p>
                                            <div class="flex items-center mt-1 space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                                <span>
                                                    <i class="fas fa-newspaper mr-1"></i>
                                                    {{ $follower->follower->posts()->count() }} posts
                                                </span>
                                                <span>
                                                    <i class="fas fa-users mr-1"></i>
                                                    {{ $follower->follower->followers()->count() }} followers
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div>
                                        @if(auth()->id() !== $follower->follower->id)
                                            @php
                                                $isFollowingBack = auth()->user()->isFollowing($follower->follower);
                                            @endphp

                                            @if($isFollowingBack)
                                                <form action="{{ route('profile.unfollow', $follower->follower) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                                                        <i class="fas fa-user-check mr-1"></i> Following
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('profile.follow', $follower->follower) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                                                        <i class="fas fa-user-plus mr-1"></i> Follow Back
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center">
                                <div class="text-gray-400 dark:text-gray-500">
                                    <i class="fas fa-users text-4xl mb-4"></i>
                                    <p class="text-lg font-medium text-gray-900 dark:text-white mb-2">No followers yet</p>
                                    <p class="text-gray-600 dark:text-gray-400">Share your posts to attract followers</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($followers->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                            {{ $followers->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Following Tab Content -->
            <div id="following-content" class="tab-content hidden">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">People You're Following</h3>
                    </div>

                    <!-- Following List -->
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($following as $follow)
                            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <!-- User Avatar -->
                                        <img src="{{ $follow->following->profile_picture }}"
                                             alt="{{ $follow->following->name }}"
                                             class="w-12 h-12 rounded-full mr-4">

                                        <!-- User Info -->
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white">
                                                <a href="{{ route('profile.show.public', $follow->following->name) }}"
                                                   class="hover:text-blue-600 dark:hover:text-blue-400">
                                                    {{ $follow->following->name }}
                                                </a>
                                            </h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                @if($follow->following->bio)
                                                    {{ Str::limit($follow->following->bio, 60) }}
                                                @else
                                                    Member since {{ $follow->following->created_at->format('M Y') }}
                                                @endif
                                            </p>
                                            <div class="flex items-center mt-1 space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                                <span>
                                                    <i class="fas fa-newspaper mr-1"></i>
                                                    {{ $follow->following->posts()->count() }} posts
                                                </span>
                                                <span>
                                                    <i class="fas fa-users mr-1"></i>
                                                    {{ $follow->following->followers()->count() }} followers
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div>
                                        @if(auth()->id() !== $follow->following->id)
                                            <form action="{{ route('profile.unfollow', $follow->following) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">
                                                    <i class="fas fa-user-times mr-1"></i> Unfollow
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center">
                                <div class="text-gray-400 dark:text-gray-500">
                                    <i class="fas fa-user-friends text-4xl mb-4"></i>
                                    <p class="text-lg font-medium text-gray-900 dark:text-white mb-2">Not following anyone</p>
                                    <p class="text-gray-600 dark:text-gray-400">Discover and follow interesting people</p>
                                    <a href="{{ route('home') }}"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 mt-4">
                                        <i class="fas fa-search mr-2"></i> Explore Posts
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($following->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                            {{ $following->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const followersTab = document.getElementById('followers-tab');
            const followingTab = document.getElementById('following-tab');
            const followersContent = document.getElementById('followers-content');
            const followingContent = document.getElementById('following-content');

            followersTab.addEventListener('click', function() {
                // Update tabs
                followersTab.classList.add('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
                followersTab.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');

                followingTab.classList.remove('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
                followingTab.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');

                // Update content
                followersContent.classList.remove('hidden');
                followingContent.classList.add('hidden');
            });

            followingTab.addEventListener('click', function() {
                // Update tabs
                followingTab.classList.add('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
                followingTab.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');

                followersTab.classList.remove('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
                followersTab.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');

                // Update content
                followingContent.classList.remove('hidden');
                followersContent.classList.add('hidden');
            });
        });
    </script>
</x-app-layout>
