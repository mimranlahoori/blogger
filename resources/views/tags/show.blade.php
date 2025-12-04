<!-- resources/views/tags/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            #{{ $tag->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                Posts tagged with #{{ $tag->name }}
                            </h1>
                            @if($tag->description)
                                <p class="text-gray-600 dark:text-gray-300 mt-2">{{ $tag->description }}</p>
                            @endif
                        </div>
                        <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm font-medium px-3 py-1 rounded-full">
                            {{ $posts->total() }} posts
                        </span>
                    </div>
                </div>

                @if($posts->count() > 0)
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($posts as $post)
                            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-750">
                                <div class="flex items-start">
                                    @if($post->image)
                                        <div class="flex-shrink-0 mr-6">
                                            <img src="{{ $post->featured_image }}"
                                                 alt="{{ $post->title }}"
                                                 class="w-32 h-24 object-cover rounded-lg">
                                        </div>
                                    @endif

                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            @foreach($post->categories->take(2) as $category)
                                                <a href="{{ route('categories.show', $category->slug) }}"
                                                   class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded mr-2">
                                                    {{ $category->name }}
                                                </a>
                                            @endforeach
                                        </div>

                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                            <a href="{{ route('posts.show', $post->slug) }}"
                                               class="hover:text-blue-600 dark:hover:text-blue-400">
                                                {{ $post->title }}
                                            </a>
                                        </h3>

                                        <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $post->excerpt }}</p>

                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <img src="{{ $post->user->profile_picture }}"
                                                     alt="{{ $post->user->name }}"
                                                     class="w-8 h-8 rounded-full mr-2">
                                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ $post->user->name }}</span>
                                            </div>

                                            <div class="flex items-center space-x-4">
                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    <i class="far fa-clock mr-1"></i> {{ $post->reading_time }}
                                                </span>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    <i class="far fa-calendar mr-1"></i> {{ $post->created_at->format('M d, Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <i class="fas fa-newspaper text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 dark:text-gray-300">No posts found with this tag.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
