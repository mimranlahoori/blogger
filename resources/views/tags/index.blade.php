<!-- resources/views/tags/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tags') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">All Tags</h1>

                    @if($tags->count() > 0)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Popular Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($tags->take(20) as $tag)
                                    <a href="{{ route('tags.show', $tag->slug) }}"
                                       class="inline-flex items-center px-4 py-2 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                                        {{ $tag->name }}
                                        <span class="ml-2 bg-blue-200 dark:bg-blue-700 text-blue-800 dark:text-blue-200 text-xs px-2 py-0.5 rounded-full">
                                            {{ $tag->posts_count }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($tags as $tag)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                        <a href="{{ route('tags.show', $tag->slug) }}"
                                           class="hover:text-blue-600 dark:hover:text-blue-400">
                                            {{ $tag->name }}
                                        </a>
                                    </h3>

                                    @if($tag->description)
                                        <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $tag->description }}</p>
                                    @endif

                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $tag->posts_count }} posts
                                        </span>
                                        <a href="{{ route('tags.show', $tag->slug) }}"
                                           class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                            View posts
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $tags->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-tags text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600 dark:text-gray-300">No tags found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
