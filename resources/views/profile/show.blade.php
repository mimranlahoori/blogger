<!-- resources/views/profile/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $user->name }}'s Profile
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Profile Header -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">
                <!-- Cover Photo -->
                <div class="h-48 bg-gradient-to-r from-blue-500 to-purple-600 relative">
                    @if($user->id === auth()->id())
                        <button class="absolute top-4 right-4 bg-white/90 dark:bg-gray-800/90 text-gray-700 dark:text-gray-300 px-3 py-1 rounded-lg text-sm hover:bg-white dark:hover:bg-gray-700">
                            <i class="fas fa-camera mr-1"></i> Edit Cover
                        </button>
                    @endif
                </div>

                <!-- Profile Info -->
                <div class="px-8 pb-8 pt-4">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between">
                        <!-- Profile Picture and Name -->
                        <div class="flex items-center md:items-end -mt-16 md:-mt-20 mb-4 md:mb-0">
                            <div class="relative">
                                <img src="{{ $user->profile_picture }}"
                                     alt="{{ $user->name }}"
                                     class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white dark:border-gray-800 shadow-lg">
                                @if($user->id === auth()->id())
                                    <button class="absolute bottom-2 right-2 bg-blue-600 text-white p-2 rounded-full hover:bg-blue-700">
                                        <i class="fas fa-camera text-sm"></i>
                                    </button>
                                @endif
                            </div>

                            <div class="ml-6">
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ $user->name }}
                                    @if($user->role === 'admin' || $user->role === 'moderator')
                                        <span class="ml-2 inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                            {{ $user->role === 'admin' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
                                               'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    @endif
                                </h1>

                                @if($user->bio)
                                    <p class="text-gray-600 dark:text-gray-300 mt-2 max-w-lg">{{ $user->bio }}</p>
                                @endif

                                <!-- Stats -->
                                <div class="flex items-center space-x-6 mt-4">
                                    <div class="text-center">
                                        <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->posts_count }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Posts</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->followers_count }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Followers</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->following_count }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Following</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-3">
                            @if($user->id === auth()->id())
                                <a href="{{ route('profile.edit') }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <i class="fas fa-edit mr-2"></i> Edit Profile
                                </a>
                                <a href="{{ route('admin.dashboard') }}"
                                   class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                                </a>
                            @else
                                <!-- Follow Button -->
                                <button id="follow-button"
                                        data-user-id="{{ $user->id }}"
                                        class="inline-flex items-center px-4 py-2 {{ $isFollowing ? 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600' : 'bg-blue-600 text-white hover:bg-blue-700' }} border border-transparent rounded-lg font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <i class="{{ $isFollowing ? 'fas fa-user-check' : 'fas fa-user-plus' }} mr-2"></i>
                                    <span id="follow-text">{{ $isFollowing ? 'Following' : 'Follow' }}</span>
                                    <span id="followers-count" class="ml-2">{{ $user->followers_count }}</span>
                                </button>

                                <!-- Message Button -->
                                <button class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <i class="fas fa-envelope mr-2"></i> Message
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Social Links -->
                    @if($user->website || $user->facebook_url || $user->twitter_url || $user->instagram_url)
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center space-x-4">
                                @if($user->website)
                                    <a href="{{ $user->website }}" target="_blank"
                                       class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                                        <i class="fas fa-globe text-lg"></i>
                                    </a>
                                @endif
                                @if($user->facebook_url)
                                    <a href="{{ $user->facebook_url }}" target="_blank"
                                       class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                                        <i class="fab fa-facebook text-lg"></i>
                                    </a>
                                @endif
                                @if($user->twitter_url)
                                    <a href="{{ $user->twitter_url }}" target="_blank"
                                       class="text-gray-600 dark:text-gray-400 hover:text-blue-400 dark:hover:text-blue-300">
                                        <i class="fab fa-twitter text-lg"></i>
                                    </a>
                                @endif
                                @if($user->instagram_url)
                                    <a href="{{ $user->instagram_url }}" target="_blank"
                                       class="text-gray-600 dark:text-gray-400 hover:text-pink-600 dark:hover:text-pink-400">
                                        <i class="fab fa-instagram text-lg"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8 px-6">
                        <a href="#posts"
                           class="{{ request()->has('tab') ? 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' : 'border-blue-500 text-blue-600 dark:text-blue-400' }} border-b-2 py-4 px-1 text-sm font-medium">
                            Posts
                        </a>
                        <a href="#about"
                           class="border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border-b-2 py-4 px-1 text-sm font-medium">
                            About
                        </a>
                        @if($user->id === auth()->id())
                            <a href="{{ route('profile.bookmarks') }}"
                               class="border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border-b-2 py-4 px-1 text-sm font-medium">
                                Bookmarks
                            </a>
                            <a href="{{ route('profile.followers') }}"
                               class="border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border-b-2 py-4 px-1 text-sm font-medium">
                                Followers
                            </a>
                        @endif
                    </nav>
                </div>
            </div>

            <!-- Posts Section -->
            <div id="posts">
                @if($posts->count() > 0)
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                            Recent Posts by {{ $user->name }}
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($posts as $post)
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow">
                                    @if($post->image)
                                        <img src="{{ $post->featured_image }}"
                                             alt="{{ $post->title }}"
                                             class="w-full h-48 object-cover">
                                    @endif
                                    <div class="p-6">
                                        <div class="flex items-center mb-2">
                                            @foreach($post->categories->take(2) as $category)
                                                <a href="{{ route('categories.show', $category->slug) }}"
                                                   class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded mr-2">
                                                    {{ $category->name }}
                                                </a>
                                            @endforeach
                                        </div>

                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                                            <a href="{{ route('posts.show', $post->slug) }}"
                                               class="hover:text-blue-600 dark:hover:text-blue-400">
                                                {{ Str::limit($post->title, 50) }}
                                            </a>
                                        </h4>

                                        <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm">
                                            {{ Str::limit($post->excerpt, 100) }}
                                        </p>

                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                                <i class="far fa-clock mr-1"></i>
                                                {{ $post->created_at->diffForHumans() }}
                                            </div>
                                            <div class="flex items-center space-x-3 text-sm text-gray-500 dark:text-gray-400">
                                                <span>
                                                    <i class="far fa-eye mr-1"></i> {{ $post->views }}
                                                </span>
                                                <span>
                                                    <i class="far fa-comment mr-1"></i> {{ $post->comments_count }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($posts->hasPages())
                            <div class="mt-6">
                                {{ $posts->links() }}
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-newspaper text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No posts yet</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ $user->name }} hasn't published any posts yet.
                        </p>
                        @if($user->id === auth()->id())
                            <a href="{{ route('admin.posts.create') }}"
                               class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-plus mr-2"></i> Create Your First Post
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- About Section -->
            <div id="about" class="hidden">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">About</h3>

                    @if($user->bio)
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-2">Bio</h4>
                            <p class="text-gray-700 dark:text-gray-300">{{ $user->bio }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Contact Info -->
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-3">Contact Information</h4>
                            <div class="space-y-3">
                                @if($user->email)
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope text-gray-400 mr-3"></i>
                                        <span class="text-gray-700 dark:text-gray-300">{{ $user->email }}</span>
                                    </div>
                                @endif
                                @if($user->phone)
                                    <div class="flex items-center">
                                        <i class="fas fa-phone text-gray-400 mr-3"></i>
                                        <span class="text-gray-700 dark:text-gray-300">{{ $user->phone }}</span>
                                    </div>
                                @endif
                                @if($user->website)
                                    <div class="flex items-center">
                                        <i class="fas fa-globe text-gray-400 mr-3"></i>
                                        <a href="{{ $user->website }}" target="_blank"
                                           class="text-blue-600 dark:text-blue-400 hover:underline">
                                            {{ $user->website }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Account Info -->
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-3">Account Information</h4>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <i class="fas fa-user text-gray-400 mr-3"></i>
                                    <span class="text-gray-700 dark:text-gray-300">Member since {{ $user->created_at->format('F Y') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-newspaper text-gray-400 mr-3"></i>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $user->posts_count }} posts published</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-users text-gray-400 mr-3"></i>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $user->followers_count }} followers</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching
            const tabLinks = document.querySelectorAll('nav a[href^="#"]');
            const tabContents = document.querySelectorAll('[id^="tab-"]');

            tabLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);

                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Show target content
                    const targetContent = document.getElementById(targetId);
                    if (targetContent) {
                        targetContent.classList.remove('hidden');
                    }

                    // Update active tab
                    tabLinks.forEach(link => {
                        link.classList.remove('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
                        link.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                    });

                    this.classList.add('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
                    this.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                });
            });

            // Follow button functionality
            const followButton = document.getElementById('follow-button');
            if (followButton) {
                followButton.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    const followText = document.getElementById('follow-text');
                    const followersCount = document.getElementById('followers-count');

                    fetch(`/profile/${userId}/toggle-follow`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update button text and style
                            if (data.isFollowing) {
                                followButton.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700');
                                followButton.classList.add('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-300', 'dark:hover:bg-gray-600');
                                followText.textContent = 'Following';
                                followButton.querySelector('i').className = 'fas fa-user-check mr-2';
                            } else {
                                followButton.classList.remove('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-300', 'dark:hover:bg-gray-600');
                                followButton.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700');
                                followText.textContent = 'Follow';
                                followButton.querySelector('i').className = 'fas fa-user-plus mr-2';
                            }

                            // Update followers count
                            followersCount.textContent = data.followersCount;

                            // Show success message
                            showNotification(data.message, 'success');
                        } else {
                            showNotification(data.error, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('An error occurred. Please try again.', 'error');
                    });
                });
            }

            function showNotification(message, type) {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'}`;
                notification.textContent = message;

                document.body.appendChild(notification);

                // Remove notification after 3 seconds
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }
        });
    </script>
    @endpush
</x-app-layout>
