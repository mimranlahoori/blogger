<!-- resources/views/categories/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $category->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Category Info</h3>

                        <div class="space-y-4">
                            @if($category->description)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Description</h4>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $category->description }}</p>
                                </div>
                            @endif

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Posts</h4>
                                <p class="text-gray-700 dark:text-gray-300">{{ $posts->total() }}</p>
                            </div>

                            @if($subcategories->count() > 0)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Subcategories</h4>
                                    <div class="space-y-2">
                                        @foreach($subcategories as $subcategory)
                                            <a href="{{ route('categories.show', $subcategory->slug) }}"
                                               class="flex items-center justify-between text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                                                <span>{{ $subcategory->name }}</span>
                                                <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-2 py-1 rounded">
                                                    {{ $subcategory->posts_count }}
                                                </span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($category->parent)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Parent Category</h4>
                                    <a href="{{ route('categories.show', $category->parent->slug) }}"
                                       class="text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ $category->parent->name }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Posts in {{ $category->name }}</h1>
                            @if($category->description)
                                <p class="text-gray-600 dark:text-gray-300 mt-2">{{ $category->description }}</p>
                            @endif
                        </div>

                        @if($posts->count() > 0)
                            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($posts as $post)
                                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700">
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
                                                    @foreach($post->categories->take(2) as $postCategory)
                                                        <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded mr-2">
                                                            {{ $postCategory->name }}
                                                        </span>
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
                                <p class="text-gray-600 dark:text-gray-300">No posts found in this category.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
