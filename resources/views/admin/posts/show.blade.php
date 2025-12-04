<!-- resources/views/admin/posts/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Post Details') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Viewing "{{ $post->title }}"</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('posts.show', $post->slug) }}"
                   target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-external-link-alt mr-2"></i> View Live
                </a>
                <a href="{{ route('admin.posts.edit', $post) }}"
                   class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <a href="{{ route('admin.posts.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Post Header -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                        @if($post->image)
                            <div class="h-64 overflow-hidden">
                                <img src="{{ $post->featured_image }}"
                                     alt="{{ $post->title }}"
                                     class="w-full h-full object-cover">
                            </div>
                        @endif

                        <div class="p-8">
                            <!-- Status Badges -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span @class([
                                    'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' => $post->status === 'draft',
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $post->status === 'published',
                                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' => $post->status === 'archived'
                                ])>
                                    {{ ucfirst($post->status) }}
                                </span>
                                @if($post->featured)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                        <i class="fas fa-star mr-1"></i> Featured
                                    </span>
                                @endif
                                @if($post->published_at && $post->published_at->isFuture())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        <i class="fas fa-clock mr-1"></i> Scheduled
                                    </span>
                                @endif
                            </div>

                            <!-- Title -->
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                                {{ $post->title }}
                            </h1>

                            <!-- Meta Info -->
                            <div class="flex flex-wrap items-center justify-between text-gray-600 dark:text-gray-400 mb-6">
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center">
                                        <img src="{{ $post->user->profile_picture }}"
                                             alt="{{ $post->user->name }}"
                                             class="w-8 h-8 rounded-full mr-2">
                                        <span class="font-medium">{{ $post->user->name }}</span>
                                    </div>
                                    <span>•</span>
                                    <span>{{ $post->reading_time }}</span>
                                    <span>•</span>
                                    <span>
                                        @if($post->published_at)
                                            {{ $post->published_at->format('F d, Y') }}
                                        @else
                                            {{ $post->created_at->format('F d, Y') }}
                                        @endif
                                    </span>
                                </div>

                                <div class="flex items-center space-x-4 mt-2 sm:mt-0">
                                    <span class="flex items-center">
                                        <i class="fas fa-eye mr-1"></i>
                                        {{ $post->views }}
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-heart mr-1"></i>
                                        {{ $post->likes_count }}
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-comment mr-1"></i>
                                        {{ $post->comments_count }}
                                    </span>
                                </div>
                            </div>

                            <!-- Categories & Tags -->
                            <div class="mb-6">
                                <!-- Categories -->
                                @if($post->categories->count() > 0)
                                    <div class="mb-3">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">Categories:</span>
                                        @foreach($post->categories as $category)
                                            <a href="{{ route('categories.show', $category->slug) }}"
                                               class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm px-3 py-1 rounded-full mr-2 mb-2 hover:bg-blue-200 dark:hover:bg-blue-800">
                                                {{ $category->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Tags -->
                                @if($post->tags->count() > 0)
                                    <div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">Tags:</span>
                                        @foreach($post->tags as $tag)
                                            <a href="{{ route('tags.show', $tag->slug) }}"
                                               class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-sm px-3 py-1 rounded-full mr-2 mb-2 hover:bg-gray-200 dark:hover:bg-gray-600">
                                                #{{ $tag->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Excerpt -->
                            @if($post->excerpt)
                                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 mb-6">
                                    <h3 class="font-medium text-gray-900 dark:text-white mb-2">Excerpt</h3>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $post->excerpt }}</p>
                                </div>
                            @endif

                            <!-- Content -->
                            <div class="prose dark:prose-invert max-w-none mb-8">
                                {!! $post->content !!}
                            </div>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                <i class="fas fa-comments mr-2"></i> Comments ({{ $post->comments->count() }})
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($post->comments->count() > 0)
                                <div class="space-y-4">
                                    @foreach($post->comments->take(5) as $comment)
                                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center">
                                                    <img src="{{ $comment->author_avatar }}"
                                                         alt="{{ $comment->author_name }}"
                                                         class="w-8 h-8 rounded-full mr-2">
                                                    <span class="font-medium text-gray-900 dark:text-white">{{ $comment->author_name }}</span>
                                                </div>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <p class="text-gray-700 dark:text-gray-300">{{ Str::limit($comment->content, 150) }}</p>
                                            <div class="mt-2 flex items-center justify-between">
                                                <span class="text-xs px-2 py-1 rounded
                                                    {{ $comment->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                                       ($comment->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                                       'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                                    {{ ucfirst($comment->status) }}
                                                </span>
                                                <a href="{{ route('admin.comments.index', ['post_id' => $post->id]) }}"
                                                   class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                                    View all comments →
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if($post->comments->count() > 5)
                                    <div class="mt-4 text-center">
                                        <a href="{{ route('admin.comments.index', ['post_id' => $post->id]) }}"
                                           class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600">
                                            View all {{ $post->comments->count() }} comments
                                            <i class="fas fa-arrow-right ml-2"></i>
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-comment-slash text-3xl text-gray-400 mb-3"></i>
                                    <p class="text-gray-600 dark:text-gray-400">No comments yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Post Stats -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Post Statistics</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $post->views }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Views</div>
                                </div>
                                <div class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $post->likes_count }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Likes</div>
                                </div>
                                <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $post->comments_count }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Comments</div>
                                </div>
                                <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                    @php
                                        $bookmarksCount = $post->bookmarks()->count();
                                    @endphp
                                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $bookmarksCount }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Bookmarks</div>
                                </div>
                            </div>

                            <div class="mt-6 space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Created</span>
                                    <span class="font-medium">{{ $post->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Last Updated</span>
                                    <span class="font-medium">{{ $post->updated_at->format('M d, Y h:i A') }}</span>
                                </div>
                                @if($post->published_at)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Published</span>
                                        <span class="font-medium">{{ $post->published_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Author Info -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Author</h3>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <img src="{{ $post->user->profile_picture }}"
                                     alt="{{ $post->user->name }}"
                                     class="w-12 h-12 rounded-full mr-4">
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $post->user->name }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $post->user->email }}</p>
                                </div>
                            </div>

                            <div class="space-y-2 text-sm">
                                <div class="flex items-center">
                                    <i class="fas fa-user-tag text-gray-400 mr-2"></i>
                                    <span class="capitalize">{{ $post->user->role }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-newspaper text-gray-400 mr-2"></i>
                                    <span>{{ $post->user->posts()->count() }} posts</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar text-gray-400 mr-2"></i>
                                    <span>Joined {{ $post->user->created_at->format('M Y') }}</span>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('admin.users.show', $post->user) }}"
                                   class="inline-flex items-center justify-center w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600">
                                    <i class="fas fa-user mr-2"></i> View Author Profile
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Info -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">SEO Information</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Meta Title</h4>
                                    <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 p-2 rounded">
                                        {{ $post->meta_title ?: $post->title }}
                                    </p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Meta Description</h4>
                                    <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 p-2 rounded">
                                        {{ $post->meta_description ?: ($post->excerpt ?: 'No meta description set') }}
                                    </p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slug</h4>
                                    <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 p-2 rounded">
                                        {{ $post->slug }}
                                    </p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">URL</h4>
                                    <a href="{{ route('posts.show', $post->slug) }}"
                                       target="_blank"
                                       class="text-sm text-blue-600 dark:text-blue-400 hover:underline break-all block bg-gray-50 dark:bg-gray-900 p-2 rounded">
                                        {{ route('posts.show', $post->slug) }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Quick Actions</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <!-- Duplicate -->
                                <form action="{{ route('admin.posts.duplicate', $post) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        <i class="fas fa-copy mr-2"></i> Duplicate Post
                                    </button>
                                </form>

                                <!-- Status Toggle -->
                                @if($post->status === 'draft')
                                    <form action="{{ route('admin.posts.bulk-action') }}" method="POST" class="w-full">
                                        @csrf
                                        <input type="hidden" name="action" value="publish">
                                        <input type="hidden" name="posts" value='["{{ $post->id }}"]'>
                                        <button type="submit"
                                                class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                            <i class="fas fa-paper-plane mr-2"></i> Publish Now
                                        </button>
                                    </form>
                                @elseif($post->status === 'published')
                                    <form action="{{ route('admin.posts.bulk-action') }}" method="POST" class="w-full">
                                        @csrf
                                        <input type="hidden" name="action" value="draft">
                                        <input type="hidden" name="posts" value='["{{ $post->id }}"]'>
                                        <button type="submit"
                                                class="w-full flex items-center justify-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                                            <i class="fas fa-edit mr-2"></i> Move to Draft
                                        </button>
                                    </form>
                                @endif

                                <!-- Featured Toggle -->
                                @if($post->featured)
                                    <form action="{{ route('admin.posts.bulk-action') }}" method="POST" class="w-full">
                                        @csrf
                                        <input type="hidden" name="action" value="unfeatured">
                                        <input type="hidden" name="posts" value='["{{ $post->id }}"]'>
                                        <button type="submit"
                                                class="w-full flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                                            <i class="fas fa-star mr-2"></i> Remove Featured
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.posts.bulk-action') }}" method="POST" class="w-full">
                                        @csrf
                                        <input type="hidden" name="action" value="featured">
                                        <input type="hidden" name="posts" value='["{{ $post->id }}"]'>
                                        <button type="submit"
                                                class="w-full flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                                            <i class="fas fa-star mr-2"></i> Make Featured
                                        </button>
                                    </form>
                                @endif

                                <!-- Delete -->
                                <form action="{{ route('admin.posts.destroy', $post) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this post? This action cannot be undone.')"
                                      class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                        <i class="fas fa-trash mr-2"></i> Delete Post
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
