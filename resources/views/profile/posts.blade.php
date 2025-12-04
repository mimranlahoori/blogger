<!-- resources/views/profile/posts.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('My Posts') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage all your blog posts</p>
            </div>
            <a href="{{ route('admin.posts.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-plus mr-2"></i> New Post
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3">
                            <i class="fas fa-newspaper text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Posts</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-lg p-3">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Published</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['published'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-lg p-3">
                            <i class="fas fa-edit text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Drafts</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['drafts'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-lg p-3">
                            <i class="fas fa-star text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Featured</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['featured'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Posts Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">My Posts</h3>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $posts->total() }} total posts
                        </div>
                    </div>
                </div>

                <!-- Posts List -->
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($posts as $post)
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <!-- Status Badge -->
                                        <span @class([
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-3',
                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' => $post->status === 'draft',
                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $post->status === 'published',
                                            'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' => $post->status === 'archived'
                                        ])>
                                            {{ ucfirst($post->status) }}
                                        </span>

                                        <!-- Featured Badge -->
                                        @if($post->featured)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 mr-3">
                                                <i class="fas fa-star mr-1"></i> Featured
                                            </span>
                                        @endif

                                        <!-- Categories -->
                                        @foreach($post->categories->take(2) as $category)
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 mr-2">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>

                                    <!-- Post Title -->
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                        <a href="{{ route('posts.show', $post->slug) }}"
                                           class="hover:text-blue-600 dark:hover:text-blue-400">
                                            {{ $post->title }}
                                        </a>
                                    </h4>

                                    <!-- Excerpt -->
                                    @if($post->excerpt)
                                        <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $post->excerpt }}</p>
                                    @endif

                                    <!-- Meta Info -->
                                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center space-x-4">
                                            <span>
                                                <i class="far fa-clock mr-1"></i>
                                                @if($post->published_at)
                                                    {{ $post->published_at->format('M d, Y') }}
                                                @else
                                                    {{ $post->created_at->format('M d, Y') }}
                                                @endif
                                            </span>
                                            <span>
                                                <i class="far fa-eye mr-1"></i>
                                                {{ $post->views }} views
                                            </span>
                                            <span>
                                                <i class="far fa-comment mr-1"></i>
                                                {{ $post->comments_count }} comments
                                            </span>
                                            <span>
                                                <i class="far fa-heart mr-1"></i>
                                                {{ $post->likes_count }} likes
                                            </span>
                                        </div>

                                        <span>{{ $post->reading_time }}</span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="ml-6 flex items-center space-x-2">
                                    <a href="{{ route('posts.show', $post->slug) }}"
                                       target="_blank"
                                       class="p-2 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/50"
                                       title="View">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>

                                    @can('update', $post)
                                        <a href="{{ route('admin.posts.edit', $post) }}"
                                           class="p-2 text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 rounded-full hover:bg-green-50 dark:hover:bg-green-900/50"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <div class="text-gray-400 dark:text-gray-500">
                                <i class="fas fa-newspaper text-4xl mb-4"></i>
                                <p class="text-lg font-medium text-gray-900 dark:text-white mb-2">No posts yet</p>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">Start sharing your thoughts with the world</p>
                                <a href="{{ route('admin.posts.create') }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-plus mr-2"></i> Create Your First Post
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($posts->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
