<!-- resources/views/categories/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">All Categories</h1>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($categories as $category)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 hover:shadow-lg transition-shadow">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        <a href="{{ route('categories.show', $category->slug) }}"
                                           class="hover:text-blue-600 dark:hover:text-blue-400">
                                            {{ $category->name }}
                                        </a>
                                    </h3>
                                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-medium px-2.5 py-0.5 rounded">
                                        {{ $category->posts_count }} posts
                                    </span>
                                </div>

                                @if($category->description)
                                    <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $category->description }}</p>
                                @endif

                                @if($category->children->count() > 0)
                                    <div class="mt-4">
                                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Subcategories</h4>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($category->children as $child)
                                                <a href="{{ route('categories.show', $child->slug) }}"
                                                   class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded hover:bg-gray-300 dark:hover:bg-gray-500">
                                                    {{ $child->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <a href="{{ route('categories.show', $category->slug) }}"
                                   class="mt-4 inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline">
                                    View posts
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    @if($categories->isEmpty())
                        <div class="text-center py-12">
                            <i class="fas fa-folder-open text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600 dark:text-gray-300">No categories found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
