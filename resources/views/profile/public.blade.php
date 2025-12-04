<!-- resources/views/profile/public.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $user->name }}'s Profile
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Profile Header -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden mb-8">
                <!-- Cover Image -->
                <div class="h-48 bg-gradient-to-r from-blue-500 to-purple-600"></div>

                <!-- Profile Info -->
                <div class="px-8 pb-8 -mt-16">
                    <div class="flex flex-col md:flex-row items-start md:items-end justify-between">
                        <!-- Avatar and Basic Info -->
                        <div class="flex items-end">
                            <div class="relative">
                                <img src="{{ $user->profile_picture }}" alt="{{ $user->name }}"
                                    class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-800 shadow-lg">
                                @if ($user->is_active)
                                    <div
                                        class="absolute bottom-2 right-2 w-4 h-4 bg-green-500 rounded-full border-2 border-white dark:border-gray-800">
                                    </div>
                                @endif
                            </div>
                            <div class="ml-6 mb-4">
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                                <p class="text-gray-600 dark:text-gray-300 mt-1">{{ $user->bio ?: 'No bio yet' }}</p>

                                <!-- User Stats -->
                                <div class="flex items-center space-x-6 mt-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                            {{ $posts->total() }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Posts</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                            {{ $user->followers_count }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Followers</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                            {{ $user->following_count }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Following</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4 md:mt-0">
                            @auth
                                @if (auth()->id() === $user->id)
                                    <a href="{{ route('profile.edit') }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <i class="fas fa-edit mr-2"></i> Edit Profile
                                    </a>
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                                    </a>
                                @else
                                    <div class="flex space-x-3">
                                        <!-- Follow Button -->
                                        @livewire('follow-button', ['user' => $user])

                                        <!-- Message Button -->
                                        {{-- <button
                                            class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                                            <i class="fas fa-envelope mr-2"></i> Message
                                        </button> --}}
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                    class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-white uppercase tracking-widest hover:bg-blue-700">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Login to Follow
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Contact Info -->
                        <div class="space-y-3">
                            <h3 class="font-semibold text-gray-900 dark:text-white flex items-center">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i> Contact Information
                            </h3>
                            @if ($user->email)
                                <div class="flex items-center text-gray-600 dark:text-gray-300">
                                    <i class="fas fa-envelope mr-3 text-gray-400"></i>
                                    <span>{{ $user->email }}</span>
                                </div>
                            @endif
                            @if ($user->phone)
                                <div class="flex items-center text-gray-600 dark:text-gray-300">
                                    <i class="fas fa-phone mr-3 text-gray-400"></i>
                                    <span>{{ $user->phone }}</span>
                                </div>
                            @endif
                            @if ($user->website)
                                <div class="flex items-center text-gray-600 dark:text-gray-300">
                                    <i class="fas fa-globe mr-3 text-gray-400"></i>
                                    <a href="{{ $user->website }}" target="_blank"
                                        class="text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ parse_url($user->website, PHP_URL_HOST) }}
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Social Media -->
                        <div class="space-y-3">
                            <h3 class="font-semibold text-gray-900 dark:text-white flex items-center">
                                <i class="fas fa-share-alt mr-2 text-blue-500"></i> Social Media
                            </h3>
                            <div class="flex space-x-4">
                                @if ($user->facebook_url)
                                    <a href="{{ $user->facebook_url }}" target="_blank"
                                        class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                                        <i class="fab fa-facebook text-2xl"></i>
                                    </a>
                                @endif
                                @if ($user->twitter_url)
                                    <a href="{{ $user->twitter_url }}" target="_blank"
                                        class="text-gray-600 dark:text-gray-300 hover:text-blue-400">
                                        <i class="fab fa-twitter text-2xl"></i>
                                    </a>
                                @endif
                                @if ($user->instagram_url)
                                    <a href="{{ $user->instagram_url }}" target="_blank"
                                        class="text-gray-600 dark:text-gray-300 hover:text-pink-600">
                                        <i class="fab fa-instagram text-2xl"></i>
                                    </a>
                                @endif
                                @if (!$user->facebook_url && !$user->twitter_url && !$user->instagram_url)
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">No social media links</p>
                                @endif
                            </div>
                        </div>

                        <!-- Member Since -->
                        <div class="space-y-3">
                            <h3 class="font-semibold text-gray-900 dark:text-white flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i> Member Since
                            </h3>
                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                <i class="fas fa-clock mr-3 text-gray-400"></i>
                                <div>
                                    <div>{{ $user->created_at->format('F d, Y') }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $user->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Tabs -->
            <div class="mb-8">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8">
                        <button id="posts-tab" class="py-4 px-1 border-b-2 font-medium text-sm active-tab">
                            <i class="fas fa-newspaper mr-2"></i> Posts ({{ $posts->total() }})
                        </button>
                        <button id="about-tab"
                            class="py-4 px-1 border-b-2 font-medium text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border-transparent">
                            <i class="fas fa-user mr-2"></i> About
                        </button>
                        @auth
                            @if (auth()->id() !== $user->id)
                                <button id="followers-tab"
                                    class="py-4 px-1 border-b-2 font-medium text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border-transparent">
                                    <i class="fas fa-users mr-2"></i> Followers
                                </button>
                            @endif
                        @endauth
                    </nav>
                </div>
            </div>

            <!-- Posts Tab Content -->
            <div id="posts-content" class="tab-content">
                @if ($posts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($posts as $post)
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow">
                                @if ($post->image)
                                    <a href="{{ route('posts.show', $post->slug) }}">
                                        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                                            class="w-full h-48 object-cover">
                                    </a>
                                @endif
                                <div class="p-6">
                                    <div class="flex items-center mb-3">
                                        @foreach ($post->categories->take(2) as $category)
                                            <a href="{{ route('categories.show', $category->slug) }}"
                                                class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded mr-2">
                                                {{ $category->name }}
                                            </a>
                                        @endforeach
                                        @if ($post->featured)
                                            <span
                                                class="inline-block bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 text-xs px-2 py-1 rounded">
                                                <i class="fas fa-star mr-1"></i> Featured
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                        <a href="{{ route('posts.show', $post->slug) }}"
                                            class="hover:text-blue-600 dark:hover:text-blue-400">
                                            {{ Str::limit($post->title, 60) }}
                                        </a>
                                    </h3>

                                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                                        {{ Str::limit($post->excerpt, 100) }}
                                    </p>

                                    <div
                                        class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                        <span>{{ $post->reading_time }}</span>
                                        <span>{{ $post->created_at->diffForHumans() }}</span>
                                    </div>

                                    <div
                                        class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <span class="flex items-center">
                                                <i class="fas fa-eye mr-1"></i> {{ $post->views }}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-heart mr-1"></i> {{ $post->likes_count }}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-comment mr-1"></i> {{ $post->comments_count }}
                                            </span>
                                        </div>
                                        <a href="{{ route('posts.show', $post->slug) }}"
                                            class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                            Read More →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if ($posts->hasPages())
                        <div class="mt-8">
                            {{ $posts->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-newspaper text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No posts yet</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ $user->name }} hasn't published any posts yet.
                        </p>
                    </div>
                @endif
            </div>

            <!-- About Tab Content -->
            <div id="about-content" class="tab-content hidden">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Bio -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">About</h3>
                            @if ($user->bio)
                                <div class="prose dark:prose-invert max-w-none">
                                    {!! nl2br(e($user->bio)) !!}
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 italic">
                                    {{ $user->name }} hasn't written a bio yet.
                                </p>
                            @endif
                        </div>

                        <!-- Stats & Info -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Profile Information
                            </h3>
                            <div class="space-y-4">
                                <!-- Role -->
                                <div
                                    class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-600 dark:text-gray-300">Role</span>
                                    <span
                                        class="font-medium text-gray-900 dark:text-white capitalize">{{ $user->role }}</span>
                                </div>

                                <!-- Account Status -->
                                <div
                                    class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-600 dark:text-gray-300">Account Status</span>
                                    <span
                                        class="font-medium {{ $user->is_active ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>

                                <!-- Email Verified -->
                                <div
                                    class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-600 dark:text-gray-300">Email Verified</span>
                                    @if ($user->email_verified)
                                        <span class="inline-flex items-center text-green-600 dark:text-green-400">
                                            <i class="fas fa-check-circle mr-2"></i> Verified
                                        </span>
                                    @else
                                        <span class="text-yellow-600 dark:text-yellow-400">Not Verified</span>
                                    @endif
                                </div>

                                <!-- Last Active -->
                                <div
                                    class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-600 dark:text-gray-300">Last Active</span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        @if ($user->last_login)
                                            {{ $user->last_login->diffForHumans() }}
                                        @else
                                            Never
                                        @endif
                                    </span>
                                </div>

                                <!-- Total Posts -->
                                <div
                                    class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-600 dark:text-gray-300">Total Posts</span>
                                    <span
                                        class="font-medium text-gray-900 dark:text-white">{{ $posts->total() }}</span>
                                </div>

                                <!-- Total Comments -->
                                <div class="flex items-center justify-between py-3">
                                    <span class="text-gray-600 dark:text-gray-300">Total Comments</span>
                                    <span
                                        class="font-medium text-gray-900 dark:text-white">{{ $user->comments()->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Followers Tab Content -->
            @auth
                @if (auth()->id() !== $user->id)
                    <div id="followers-content" class="tab-content hidden">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Followers -->
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                            Followers ({{ $user->followers_count }})
                                        </h3>
                                        @if ($user->followers->count() > 0)
                                            <div class="space-y-3">
                                                @foreach ($user->followers->take(5) as $follower)
                                                    <div
                                                        class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                                        <div class="flex items-center">
                                                            <img src="{{ $follower->follower->profile_picture }}"
                                                                alt="{{ $follower->follower->name }}"
                                                                class="w-10 h-10 rounded-full mr-3">
                                                            <div>
                                                                <a href="{{ route('profile.show.public', $follower->follower->name) }}"
                                                                    class="font-medium text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                                                                    {{ $follower->follower->name }}
                                                                </a>
                                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                    {{ $follower->follower->posts()->count() }} posts
                                                                </p>
                                                            </div>
                                                        </div>
                                                        @if (auth()->id() !== $follower->follower_id)
                                                            @livewire('follow-button', ['user' => $follower->follower])
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                            @if ($user->followers->count() > 5)
                                                <div class="mt-4 text-center">
                                                    <a href="#"
                                                        class="text-blue-600 dark:text-blue-400 hover:underline">
                                                        View all {{ $user->followers_count }} followers
                                                    </a>
                                                </div>
                                            @endif
                                        @else
                                            <p class="text-gray-500 dark:text-gray-400">No followers yet</p>
                                        @endif
                                    </div>

                                    <!-- Following -->
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                            Following ({{ $user->following_count }})
                                        </h3>
                                        @if ($user->following->count() > 0)
                                            <div class="space-y-3">
                                                @foreach ($user->following->take(5) as $following)
                                                    <div
                                                        class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                                        <div class="flex items-center">
                                                            <img src="{{ $following->following->profile_picture }}"
                                                                alt="{{ $following->following->name }}"
                                                                class="w-10 h-10 rounded-full mr-3">
                                                            <div>
                                                                <a href="{{ route('profile.show.public', $following->following->name) }}"
                                                                    class="font-medium text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                                                                    {{ $following->following->name }}
                                                                </a>
                                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                    {{ $following->following->posts()->count() }} posts
                                                                </p>
                                                            </div>
                                                        </div>
                                                        @if (auth()->id() !== $following->following_id)
                                                            <livewire:follow-button :user="$following->following" />

                                                            <!-- Ensure Livewire is installed and configured correctly -->
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                            @if ($user->following->count() > 5)
                                                <div class="mt-4 text-center">
                                                    <a href="#"
                                                        class="text-blue-600 dark:text-blue-400 hover:underline">
                                                        View all {{ $user->following_count }} following
                                                    </a>
                                                </div>
                                            @endif
                                        @else
                                            <p class="text-gray-500 dark:text-gray-400">Not following anyone yet</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Tab switching functionality
                const tabs = {
                    'posts-tab': 'posts-content',
                    'about-tab': 'about-content',
                    'followers-tab': 'followers-content'
                };

                // Initialize first tab as active
                document.getElementById('posts-tab').classList.add('border-blue-500', 'text-blue-600',
                    'dark:text-blue-400');

                // Add click event to all tabs
                Object.keys(tabs).forEach(tabId => {
                    const tab = document.getElementById(tabId);
                    if (tab) {
                        tab.addEventListener('click', function() {
                            // Remove active classes from all tabs
                            Object.keys(tabs).forEach(id => {
                                const t = document.getElementById(id);
                                const content = document.getElementById(tabs[id]);

                                if (t) {
                                    t.classList.remove('border-blue-500', 'text-blue-600',
                                        'dark:text-blue-400');
                                    t.classList.add('border-transparent', 'text-gray-500',
                                        'dark:text-gray-400');
                                }

                                if (content) {
                                    content.classList.add('hidden');
                                }
                            });

                            // Add active classes to clicked tab
                            this.classList.remove('border-transparent', 'text-gray-500',
                                'dark:text-gray-400');
                            this.classList.add('border-blue-500', 'text-blue-600',
                                'dark:text-blue-400');

                            // Show corresponding content
                            const contentId = tabs[tabId];
                            document.getElementById(contentId).classList.remove('hidden');
                        });
                    }
                });

                // Handle URL hash for direct tab access
                const hash = window.location.hash.substring(1);
                if (hash && tabs[hash + '-tab']) {
                    document.getElementById(hash + '-tab')?.click();
                }
            });
        </script>

        <style>
            .active-tab {
                @apply border-blue-500 text-blue-600 dark:text-blue-400;
            }

            .tab-content {
                animation: fadeIn 0.3s ease-in-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            .prose {
                max-width: none;
            }

            .prose p {
                margin-top: 0.5em;
                margin-bottom: 0.5em;
            }
        </style>
    @endpush
</x-app-layout>
