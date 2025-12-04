<!-- resources/views/posts/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Latest Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Featured Posts -->
                    @if($featuredPosts->count() > 0)
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Featured Posts</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($featuredPosts as $post)
                                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                                        @if($post->image)
                                            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                                        @endif
                                        <div class="p-6">
                                            <div class="flex items-center mb-2">
                                                @foreach($post->categories as $category)
                                                    <a href="{{ route('posts.index', ['category' => $category->slug]) }}" class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-2">
                                                        {{ $category->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                                <a href="{{ route('posts.show', $post->slug) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                                    {{ $post->title }}
                                                </a>
                                            </h3>
                                            <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $post->excerpt }}</p>
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <img src="{{ $post->user->profile_picture }}" alt="{{ $post->user->name }}" class="w-8 h-8 rounded-full mr-2">
                                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $post->user->name }}</span>
                                                </div>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- All Posts -->
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Latest Posts</h2>
                    @if($posts->count() > 0)
                        <div class="space-y-6">
                            @foreach($posts as $post)
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                                    <div class="p-6">
                                        <div class="flex items-center mb-2">
                                            @foreach($post->categories->take(2) as $category)
                                                <a href="{{ route('posts.index', ['category' => $category->slug]) }}" class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded mr-2">
                                                    {{ $category->name }}
                                                </a>
                                            @endforeach
                                            @if($post->categories->count() > 2)
                                                <span class="text-xs text-gray-500">+{{ $post->categories->count() - 2 }} more</span>
                                            @endif
                                        </div>

                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                            <a href="{{ route('posts.show', $post->slug) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                                {{ $post->title }}
                                            </a>
                                        </h3>

                                        <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $post->excerpt }}</p>

                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex items-center">
                                                    <img src="{{ $post->user->profile_picture }}" alt="{{ $post->user->name }}" class="w-8 h-8 rounded-full mr-2">
                                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $post->user->name }}</span>
                                                </div>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $post->reading_time }}</span>
                                            </div>
                                            <div class="flex items-center space-x-4">
                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    <i class="far fa-eye mr-1"></i> {{ $post->views }}
                                                </span>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    <i class="far fa-comment mr-1"></i> {{ $post->comments_count }}
                                                </span>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    <i class="far fa-heart mr-1"></i> {{ $post->likes_count }}
                                                </span>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-600 dark:text-gray-300">No posts found.</p>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Search -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Search</h3>
                        <form action="{{ route('posts.index') }}" method="GET">
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Search posts...">
                                <button type="submit" class="absolute right-2 top-2 text-gray-400 hover:text-blue-500">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Categories -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Categories</h3>
                        <div class="space-y-2">
                            @foreach($categories as $category)
                                <a href="{{ route('posts.index', ['category' => $category->slug]) }}"
                                   class="flex items-center justify-between text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                                    <span>{{ $category->name }}</span>
                                    <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs px-2 py-1 rounded">
                                        {{ $category->post_count }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Popular Tags -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Popular Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($popularTags as $tag)
                                <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}"
                                   class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-sm px-3 py-1 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-800 dark:hover:text-blue-200">
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
