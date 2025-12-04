<!-- resources/views/profile/bookmarks.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('My Bookmarks') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Posts you've saved for later</p>
            </div>
            <a href="{{ route('profile.edit') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-arrow-left mr-2"></i> Back to Profile
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($bookmarks->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-3">
                        <div class="space-y-6">
                            @foreach($bookmarks as $bookmark)
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow">
                                    <div class="md:flex">
                                        @if($bookmark->post->image)
                                            <div class="md:w-48 flex-shrink-0">
                                                <a href="{{ route('posts.show', $bookmark->post->slug) }}">
                                                    <img src="{{ $bookmark->post->featured_image }}"
                                                         alt="{{ $bookmark->post->title }}"
                                                         class="w-full h-48 md:h-full object-cover">
                                                </a>
                                            </div>
                                        @endif
                                        <div class="flex-1 p-6">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center">
                                                    @foreach($bookmark->post->categories->take(2) as $category)
                                                        <a href="{{ route('categories.show', $category->slug) }}"
                                                           class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded mr-2">
                                                            {{ $category->name }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    Saved {{ $bookmark->created_at->diffForHumans() }}
                                                </span>
                                            </div>

                                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                                <a href="{{ route('posts.show', $bookmark->post->slug) }}"
                                                   class="hover:text-blue-600 dark:hover:text-blue-400">
                                                    {{ $bookmark->post->title }}
                                                </a>
                                            </h3>

                                            <p class="text-gray-600 dark:text-gray-300 mb-4">
                                                {{ Str::limit($bookmark->post->excerpt, 150) }}
                                            </p>

                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <img src="{{ $bookmark->post->user->profile_picture }}"
                                                         alt="{{ $bookmark->post->user->name }}"
                                                         class="w-8 h-8 rounded-full mr-2">
                                                    <a href="{{ route('profile.show.public', $bookmark->post->user->name) }}"
                                                       class="text-sm text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                                                        {{ $bookmark->post->user->name }}
                                                    </a>
                                                </div>

                                                <div class="flex items-center space-x-4">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                                        <i class="far fa-clock mr-1"></i> {{ $bookmark->post->reading_time }}
                                                    </span>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                                        <i class="far fa-calendar mr-1"></i> {{ $bookmark->post->created_at->format('M d, Y') }}
                                                    </span>
                                                    <form action="{{ route('bookmarks.toggle', $bookmark->post) }}"
                                                          method="POST"
                                                          class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
                                                                title="Remove bookmark">
                                                            <i class="fas fa-bookmark"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($bookmarks->hasPages())
                            <div class="mt-8">
                                {{ $bookmarks->links() }}
                            </div>
                        @endif
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <!-- Stats -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Bookmark Stats</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">Total Bookmarks</span>
                                    <span class="font-bold text-blue-600 dark:text-blue-400">{{ $bookmarks->total() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">This Month</span>
                                    @php
                                        $monthCount = auth()->user()->bookmarks()
                                            ->whereMonth('created_at', now()->month)
                                            ->count();
                                    @endphp
                                    <span class="font-medium">{{ $monthCount }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">This Week</span>
                                    @php
                                        $weekCount = auth()->user()->bookmarks()
                                            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                                            ->count();
                                    @endphp
                                    <span class="font-medium">{{ $weekCount }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <a href="{{ route('posts.index') }}"
                                   class="flex items-center justify-center w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-plus mr-2"></i> Browse More Posts
                                </a>


                            </div>
                        </div>

                        <!-- Most Bookmarked Categories -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mt-6">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Top Categories</h3>
                            @php
                                $topCategories = auth()->user()->bookmarkedPosts()
                                    ->with('categories')
                                    ->get()
                                    ->flatMap(function($post) {
                                        return $post->categories;
                                    })
                                    ->groupBy('id')
                                    ->map(function($group) {
                                        return [
                                            'category' => $group->first(),
                                            'count' => $group->count()
                                        ];
                                    })
                                    ->sortByDesc('count')
                                    ->take(5);
                            @endphp

                            @if($topCategories->count() > 0)
                                <div class="space-y-2">
                                    @foreach($topCategories as $data)
                                        <a href="{{ route('categories.show', $data['category']->slug) }}"
                                           class="flex items-center justify-between p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded">
                                            <span class="text-gray-700 dark:text-gray-300">{{ $data['category']->name }}</span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $data['count'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-sm">No category data yet</p>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-16">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-blue-100 dark:bg-blue-900 mb-6">
                        <i class="fas fa-bookmark text-4xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-3">No bookmarks yet</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                        When you find interesting posts, click the bookmark icon to save them here for later reading.
                    </p>
                    <div class="space-x-4">
                        <a href="{{ route('posts.index') }}"
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-newspaper mr-2"></i> Browse Posts
                        </a>
                        <a href="{{ route('profile.edit') }}"
                           class="inline-flex items-center px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                            <i class="fas fa-user mr-2"></i> Back to Profile
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>


</x-app-layout>
