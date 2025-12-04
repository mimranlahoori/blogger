<!-- resources/views/search/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Search Results for "{{ $query }}"
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Box -->
            <div class="mb-8">
                <form action="{{ route('search') }}" method="GET" class="relative">
                    <div class="relative">
                        <input type="text"
                               name="q"
                               value="{{ $query }}"
                               class="w-full px-6 py-4 text-lg border border-gray-300 dark:border-gray-700 rounded-full focus:ring-4 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-white pl-12"
                               placeholder="Search posts, categories, tags, users..."
                               autocomplete="off">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <button type="submit"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabs -->
            <div class="mb-8">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8">
                        <a href="#posts"
                           class="{{ request('posts_page') || !request('categories_page') && !request('tags_page') && !request('users_page') ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }} border-b-2 py-4 px-1 text-sm font-medium">
                            Posts ({{ $posts->total() }})
                        </a>
                        <a href="#categories"
                           class="{{ request('categories_page') ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }} border-b-2 py-4 px-1 text-sm font-medium">
                            Categories ({{ $categories->total() }})
                        </a>
                        <a href="#tags"
                           class="{{ request('tags_page') ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }} border-b-2 py-4 px-1 text-sm font-medium">
                            Tags ({{ $tags->total() }})
                        </a>
                        <a href="#users"
                           class="{{ request('users_page') ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }} border-b-2 py-4 px-1 text-sm font-medium">
                            Users ({{ $users->total() }})
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Posts Results -->
            @if($posts->count() > 0)
                <div id="posts" class="mb-12">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Posts</h3>
                    <div class="space-y-6">
                        @foreach($posts as $post)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                                <div class="p-6">
                                    <div class="flex items-center mb-2">
                                        @foreach($post->categories->take(2) as $category)
                                            <a href="{{ route('categories.show', $category->slug) }}"
                                               class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded mr-2">
                                                {{ $category->name }}
                                            </a>
                                        @endforeach
                                    </div>

                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                        <a href="{{ route('posts.show', $post->slug) }}"
                                           class="hover:text-blue-600 dark:hover:text-blue-400">
                                            {{ $post->title }}
                                        </a>
                                    </h4>

                                    <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $post->excerpt }}</p>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <img src="{{ $post->user->profile_picture }}"
                                                 alt="{{ $post->user->name }}"
                                                 class="w-8 h-8 rounded-full mr-2">
                                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ $post->user->name }}</span>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $post->reading_time }}</span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($posts->hasPages())
                        <div class="mt-6">
                            {{ $posts->appends(['q' => $query])->links() }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Categories Results -->
            @if($categories->count() > 0)
                <div id="categories" class="mb-12">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Categories</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($categories as $category)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        <a href="{{ route('categories.show', $category->slug) }}"
                                           class="hover:text-blue-600 dark:hover:text-blue-400">
                                            {{ $category->name }}
                                        </a>
                                    </h4>
                                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-medium px-2.5 py-0.5 rounded">
                                        {{ $category->posts_count }} posts
                                    </span>
                                </div>

                                @if($category->description)
                                    <p class="text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($category->description, 100) }}</p>
                                @endif

                                <a href="{{ route('categories.show', $category->slug) }}"
                                   class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline">
                                    View posts
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    @if($categories->hasPages())
                        <div class="mt-6">
                            {{ $categories->appends(['q' => $query, 'categories_page' => $categories->currentPage()])->links() }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Tags Results -->
            @if($tags->count() > 0)
                <div id="tags" class="mb-12">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Tags</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($tags as $tag)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        <a href="{{ route('tags.show', $tag->slug) }}"
                                           class="hover:text-blue-600 dark:hover:text-blue-400">
                                            #{{ $tag->name }}
                                        </a>
                                    </h4>
                                    <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-medium px-2.5 py-0.5 rounded">
                                        {{ $tag->posts_count }} posts
                                    </span>
                                </div>

                                @if($tag->description)
                                    <p class="text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($tag->description, 100) }}</p>
                                @endif

                                <a href="{{ route('tags.show', $tag->slug) }}"
                                   class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline">
                                    View posts
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    @if($tags->hasPages())
                        <div class="mt-6">
                            {{ $tags->appends(['q' => $query, 'tags_page' => $tags->currentPage()])->links() }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Users Results -->
            @if($users->count() > 0)
                <div id="users" class="mb-12">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Users</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($users as $user)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                                <div class="flex items-center mb-4">
                                    <img src="{{ $user->profile_picture }}"
                                         alt="{{ $user->name }}"
                                         class="w-12 h-12 rounded-full mr-4">
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white">
                                            <a href="{{ route('profile.show', $user->name) }}"
                                               class="hover:text-blue-600 dark:hover:text-blue-400">
                                                {{ $user->name }}
                                            </a>
                                        </h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                    </div>
                                </div>

                                @if($user->bio)
                                    <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm">{{ Str::limit($user->bio, 80) }}</p>
                                @endif

                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $user->posts()->count() }} posts
                                    </div>
                                    <a href="{{ route('profile.show', $user->name) }}"
                                       class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                        View Profile
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($users->hasPages())
                        <div class="mt-6">
                            {{ $users->appends(['q' => $query, 'users_page' => $users->currentPage()])->links() }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- No Results -->
            @if($posts->count() === 0 && $categories->count() === 0 && $tags->count() === 0 && $users->count() === 0)
                <div class="text-center py-12">
                    <i class="fas fa-search text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No results found</h3>
                    <p class="text-gray-600 dark:text-gray-300">Try different keywords or check your spelling</p>
                    <div class="mt-6">
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            <i class="fas fa-home mr-2"></i> Back to Home
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
