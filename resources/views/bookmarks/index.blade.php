<!-- resources/views/bookmarks/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('My Bookmarks') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Posts you've saved for later</p>
            </div>
            <a href="{{ route('home') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-arrow-left mr-2"></i> Back to Posts
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($bookmarks->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($bookmarks as $bookmark)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                            @if($bookmark->post->image)
                                <img src="{{ $bookmark->post->featured_image }}"
                                     alt="{{ $bookmark->post->title }}"
                                     class="w-full h-48 object-cover">
                            @endif
                            <div class="p-6">
                                <!-- Categories -->
                                <div class="flex flex-wrap gap-1 mb-3">
                                    @foreach($bookmark->post->categories->take(2) as $category)
                                        <a href="{{ route('categories.show', $category->slug) }}"
                                           class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded">
                                            {{ $category->name }}
                                        </a>
                                    @endforeach
                                </div>

                                <!-- Title -->
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                                    <a href="{{ route('posts.show', $bookmark->post->slug) }}"
                                       class="hover:text-blue-600 dark:hover:text-blue-400">
                                        {{ $bookmark->post->title }}
                                    </a>
                                </h3>

                                <!-- Excerpt -->
                                <p class="text-gray-600 dark:text-gray-300 mb-4">
                                    {{ Str::limit($bookmark->post->excerpt, 100) }}
                                </p>

                                <!-- Meta Info -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <img src="{{ $bookmark->post->user->profile_picture }}"
                                             alt="{{ $bookmark->post->user->name }}"
                                             class="w-8 h-8 rounded-full mr-2">
                                        <span class="text-sm text-gray-600 dark:text-gray-300">
                                            {{ $bookmark->post->user->name }}
                                        </span>
                                    </div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $bookmark->post->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <!-- Actions -->
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                    <a href="{{ route('posts.show', $bookmark->post->slug) }}"
                                       class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                        Read Post →
                                    </a>

                                    <form action="{{ route('bookmarks.destroy', $bookmark) }}"
                                          method="POST"
                                          onsubmit="return confirm('Remove this bookmark?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $bookmarks->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                    <i class="fas fa-bookmark text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No bookmarks yet</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        Save interesting posts by clicking the bookmark icon on any post
                    </p>
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        <i class="fas fa-newspaper mr-2"></i> Browse Posts
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
