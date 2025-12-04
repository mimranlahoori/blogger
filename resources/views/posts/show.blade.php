<!-- resources/views/posts/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $post->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2">
                    <!-- Post Content -->
                    <article class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-8">
                        @if($post->image)
                            <div class="h-80 lg:h-96 overflow-hidden">
                                <img src="{{ $post->featured_image }}"
                                     alt="{{ $post->title }}"
                                     class="w-full h-full object-cover">
                            </div>
                        @endif

                        <div class="p-6 lg:p-8">
                            <!-- Categories -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach($post->categories as $category)
                                    <a href="{{ route('categories.show', $category->slug) }}"
                                       class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm px-3 py-1 rounded-full hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>

                            <!-- Title -->
                            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-4">
                                {{ $post->title }}
                            </h1>

                            <!-- Post Meta -->
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6 space-y-4 lg:space-y-0">
                                <div class="flex items-center">
                                    <img src="{{ $post->user->profile_picture }}"
                                         alt="{{ $post->user->name }}"
                                         class="w-10 h-10 lg:w-12 lg:h-12 rounded-full mr-3 lg:mr-4">
                                    <div>
                                        <a href="{{ route('profile.show.public', $post->user->name) }}"
                                           class="font-semibold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                            {{ $post->user->name }}
                                        </a>
                                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                            <span>{{ $post->created_at->format('F d, Y') }}</span>
                                            <span class="mx-2">•</span>
                                            <span>{{ $post->reading_time }}</span>
                                            <span class="mx-2">•</span>
                                            <span>{{ $post->views }} views</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center space-x-4">
                                    <!-- Livewire Like Button -->
                                    <livewire:like-button :post="$post" />

                                    <!-- Bookmark Button -->
                                    @auth
                                        <form action="{{ route('bookmarks.toggle', $post) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="flex items-center space-x-2 text-gray-600 dark:text-gray-300 hover:text-yellow-500 transition-colors">
                                                @if(auth()->user()->bookmarks()->where('post_id', $post->id)->exists())
                                                    <i class="fas fa-bookmark text-yellow-500"></i>
                                                    <span class="hidden lg:inline">Saved</span>
                                                @else
                                                    <i class="far fa-bookmark"></i>
                                                    <span class="hidden lg:inline">Save</span>
                                                @endif
                                            </button>
                                        </form>
                                    @endauth

                                    <!-- Share Button -->
                                    <div class="relative">
                                        <button onclick="toggleShareDropdown()"
                                                class="flex items-center space-x-2 text-gray-600 dark:text-gray-300 hover:text-blue-500 transition-colors">
                                            <i class="fas fa-share-alt"></i>
                                            <span class="hidden lg:inline">Share</span>
                                        </button>

                                        <div id="share-dropdown"
                                             class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg z-10 border border-gray-200 dark:border-gray-700">
                                            <div class="py-2">
                                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                                   target="_blank"
                                                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                    <i class="fab fa-facebook mr-3 text-blue-600"></i>
                                                    Facebook
                                                </a>
                                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
                                                   target="_blank"
                                                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                    <i class="fab fa-twitter mr-3 text-blue-400"></i>
                                                    Twitter
                                                </a>
                                                <button onclick="copyLink()"
                                                        class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                    <i class="fas fa-link mr-3 text-gray-500"></i>
                                                    Copy Link
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Post Content -->
                            <div class="prose prose-lg dark:prose-invert max-w-none mb-8 text-gray-800 dark:text-gray-200">
                                {!! $post->content !!}
                            </div>

                            <!-- Tags -->
                            @if($post->tags->count() > 0)
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mb-6">
                                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Tags:</h3>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($post->tags as $tag)
                                            <a href="{{ route('tags.show', $tag->slug) }}"
                                               class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm px-3 py-1 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-800 dark:hover:text-blue-200 transition-colors">
                                                #{{ $tag->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Admin Actions -->
                            @can('update', $post)
                                <div class="flex items-center justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <a href="{{ route('admin.posts.edit', $post) }}"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                                        <i class="fas fa-edit mr-2"></i> Edit Post
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </article>

                    <!-- Comments Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                        <div class="px-6 lg:px-8 py-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                Comments ({{ $post->comments_count }})
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300">Join the conversation</p>
                        </div>

                        <!-- Comment Form -->
                        @auth
                            <div class="px-6 lg:px-8 py-6 border-b border-gray-200 dark:border-gray-700">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Add a Comment</h4>
                                <form action="{{ route('comments.store', $post) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <textarea name="content"
                                                  rows="4"
                                                  class="w-full px-3 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-white dark:placeholder-gray-400"
                                                  placeholder="Write your comment here..."
                                                  required></textarea>
                                        @error('content')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit"
                                            class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                        Post Comment
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="px-6 lg:px-8 py-8 border-b border-gray-200 dark:border-gray-700 text-center">
                                <i class="fas fa-comment text-3xl text-gray-400 mb-3"></i>
                                <p class="text-gray-600 dark:text-gray-300 mb-4">
                                    Please <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">login</a>
                                    to leave a comment.
                                </p>
                            </div>
                        @endauth

                        <!-- Comments List -->
                        <div class="px-6 lg:px-8 py-6">
                            @if($post->comments->count() > 0)
                                <div class="space-y-6">
                                    @foreach($post->comments as $comment)
                                        @include('comments.partials.comment', ['comment' => $comment])
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-comments text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                                    <p class="text-gray-600 dark:text-gray-300">No comments yet. Be the first to comment!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column - Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Author Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">About the Author</h3>
                        <div class="flex items-center mb-4">
                            <img src="{{ $post->user->profile_picture }}"
                                 alt="{{ $post->user->name }}"
                                 class="w-16 h-16 rounded-full mr-4">
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">{{ $post->user->name }}</h4>
                                @if($post->user->bio)
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ Str::limit($post->user->bio, 80) }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $post->user->posts()->count() }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Posts</div>
                            </div>
                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $post->user->followers_count }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Followers</div>
                            </div>
                        </div>
                        <a href="{{ route('profile.show.public', $post->user->name) }}"
                           class="block w-full text-center px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                            View Profile
                        </a>
                    </div>

                    <!-- Recent Posts -->
                    @php
                        $recentPosts = \App\Models\Post::published()
                            ->where('id', '!=', $post->id)
                            ->with('user')
                            ->latest()
                            ->take(5)
                            ->get();
                    @endphp

                    @if($recentPosts->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Posts</h3>
                            </div>
                            <div class="p-4">
                                <div class="space-y-4">
                                    @foreach($recentPosts as $recentPost)
                                        <a href="{{ route('posts.show', $recentPost->slug) }}"
                                           class="block group hover:bg-gray-50 dark:hover:bg-gray-900 p-3 rounded-lg transition-colors">
                                            <div class="flex items-start">
                                                @if($recentPost->image)
                                                    <div class="flex-shrink-0 mr-3">
                                                        <img src="{{ $recentPost->featured_image }}"
                                                             alt="{{ $recentPost->title }}"
                                                             class="w-16 h-16 object-cover rounded">
                                                    </div>
                                                @endif
                                                <div>
                                                    <h4 class="font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                                        {{ Str::limit($recentPost->title, 50) }}
                                                    </h4>
                                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        <span>{{ $recentPost->created_at->format('M d') }}</span>
                                                        <span class="mx-2">•</span>
                                                        <span>{{ $recentPost->reading_time }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Related Posts -->
                    @if($relatedPosts->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Related Posts</h3>
                            </div>
                            <div class="p-4">
                                <div class="space-y-4">
                                    @foreach($relatedPosts as $relatedPost)
                                        <a href="{{ route('posts.show', $relatedPost->slug) }}"
                                           class="block group hover:bg-gray-50 dark:hover:bg-gray-900 p-3 rounded-lg transition-colors">
                                            <div class="flex items-start">
                                                @if($relatedPost->image)
                                                    <div class="flex-shrink-0 mr-3">
                                                        <img src="{{ $relatedPost->featured_image }}"
                                                             alt="{{ $relatedPost->title }}"
                                                             class="w-16 h-16 object-cover rounded">
                                                    </div>
                                                @endif
                                                <div>
                                                    <h4 class="font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                                        {{ Str::limit($relatedPost->title, 50) }}
                                                    </h4>
                                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                                        {{ Str::limit($relatedPost->excerpt, 60) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Popular Posts -->
                    @php
                        $popularPosts = \App\Models\Post::published()
                            ->where('id', '!=', $post->id)
                            ->with('user')
                            ->orderBy('views', 'desc')
                            ->take(5)
                            ->get();
                    @endphp

                    @if($popularPosts->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Popular Posts</h3>
                            </div>
                            <div class="p-4">
                                <div class="space-y-4">
                                    @foreach($popularPosts as $popularPost)
                                        <a href="{{ route('posts.show', $popularPost->slug) }}"
                                           class="block group hover:bg-gray-50 dark:hover:bg-gray-900 p-3 rounded-lg transition-colors">
                                            <div class="flex items-center justify-between">
                                                <div class="flex-1">
                                                    <h4 class="font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                                        {{ Str::limit($popularPost->title, 40) }}
                                                    </h4>
                                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        <span>{{ $popularPost->views }} views</span>
                                                    </div>
                                                </div>
                                                @if($popularPost->image)
                                                    <div class="flex-shrink-0 ml-3">
                                                        <img src="{{ $popularPost->featured_image }}"
                                                             alt="{{ $popularPost->title }}"
                                                             class="w-12 h-12 object-cover rounded">
                                                    </div>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Categories -->
                    @php
                        $popularCategories = \App\Models\Category::withCount('posts')
                            ->orderBy('posts_count', 'desc')
                            ->take(8)
                            ->get();
                    @endphp

                    @if($popularCategories->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Categories</h3>
                            </div>
                            <div class="p-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($popularCategories as $category)
                                        <a href="{{ route('categories.show', $category->slug) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-800 dark:hover:text-blue-200 transition-colors">
                                            {{ $category->name }}
                                            <span class="ml-1.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs px-1.5 py-0.5 rounded-full">
                                                {{ $category->posts_count }}
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Newsletter Signup -->
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow p-6 text-white">
                        <div class="text-center">
                            <i class="fas fa-envelope text-3xl mb-4"></i>
                            <h3 class="text-xl font-bold mb-2">Stay Updated</h3>
                            <p class="text-blue-100 mb-4">Subscribe to our newsletter for the latest posts</p>
                            <form class="space-y-3">
                                <input type="email"
                                       placeholder="Your email address"
                                       class="w-full px-4 py-2.5 rounded-lg bg-white/20 placeholder-blue-200 text-white border border-white/30 focus:outline-none focus:ring-2 focus:ring-white">
                                <button type="submit"
                                        class="w-full px-4 py-2.5 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white transition-colors">
                                    Subscribe
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleShareDropdown() {
            document.getElementById('share-dropdown').classList.toggle('hidden');
        }

        function copyLink() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                // Show a nicer notification
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                notification.textContent = 'Link copied to clipboard!';
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 3000);

                document.getElementById('share-dropdown').classList.add('hidden');
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const shareDropdown = document.getElementById('share-dropdown');
            const shareButton = document.querySelector('[onclick="toggleShareDropdown()"]');

            if (!shareButton.contains(event.target) && !shareDropdown.contains(event.target)) {
                shareDropdown.classList.add('hidden');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>

    <style>
        /* Custom prose styles for better readability in dark mode */
        .dark .prose {
            color: #e5e7eb;
        }

        .dark .prose h1,
        .dark .prose h2,
        .dark .prose h3,
        .dark .prose h4 {
            color: #f3f4f6;
        }

        .dark .prose p {
            color: #d1d5db;
        }

        .dark .prose strong {
            color: #f9fafb;
        }

        .dark .prose a {
            color: #60a5fa;
        }

        .dark .prose blockquote {
            border-left-color: #4b5563;
            color: #9ca3af;
        }

        .dark .prose code {
            background-color: #374151;
            color: #e5e7eb;
        }

        .dark .prose pre {
            background-color: #1f2937;
            color: #e5e7eb;
        }

        .dark .prose ul > li::before {
            background-color: #6b7280;
        }

        .dark .prose ol > li::before {
            color: #9ca3af;
        }

        .dark .prose hr {
            border-color: #4b5563;
        }

        /* Improve table styles in dark mode */
        .dark .prose table {
            border-color: #4b5563;
        }

        .dark .prose thead {
            background-color: #374151;
            color: #f3f4f6;
        }

        .dark .prose tbody tr {
            border-bottom-color: #4b5563;
        }

        .dark .prose tbody tr:nth-child(even) {
            background-color: #1f2937;
        }

        /* Improve image contrast in dark mode */
        .dark .prose img {
            opacity: 0.95;
        }

        /* Style the newsletter section in dark mode */
        .dark .from-blue-500 {
            --tw-gradient-from: #3b82f6;
        }

        .dark .to-purple-600 {
            --tw-gradient-to: #7c3aed;
        }
    </style>
</x-app-layout>
