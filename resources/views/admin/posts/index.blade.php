<!-- resources/views/admin/posts/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manage Posts') }}
            </h2>
            <a href="{{ route('admin.posts.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-plus mr-2"></i> New Post
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Filters -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <form method="GET" class="space-y-4 md:space-y-0 md:flex md:space-x-4">
                        <div class="flex-1">
                            <x-text-input type="text" name="search" placeholder="Search posts..."
                                          class="w-full" value="{{ request('search') }}" />
                        </div>

                        <div>
                            <select name="status" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                                   dark:text-gray-300 focus:border-indigo-500
                                                   dark:focus:border-indigo-600 focus:ring-indigo-500
                                                   dark:focus:ring-indigo-600 rounded-md shadow-sm w-full">
                                <option value="">All Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>

                        <div>
                            <select name="category" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                                   dark:text-gray-300 focus:border-indigo-500
                                                   dark:focus:border-indigo-600 focus:ring-indigo-500
                                                   dark:focus:ring-indigo-600 rounded-md shadow-sm w-full">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-primary-button type="submit">Filter</x-primary-button>
                            <a href="{{ route('admin.posts.index') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="bg-blue-50 dark:bg-gray-900 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3">
                                <i class="fas fa-newspaper text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Posts</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $posts->total() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-50 dark:bg-gray-900 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-lg p-3">
                                <i class="fas fa-check-circle text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Published</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ \App\Models\Post::where('status', 'published')->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-yellow-50 dark:bg-gray-900 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-lg p-3">
                                <i class="fas fa-edit text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Drafts</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ \App\Models\Post::where('status', 'draft')->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-purple-50 dark:bg-gray-900 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-500 rounded-lg p-3">
                                <i class="fas fa-star text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Featured</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ \App\Models\Post::where('featured', true)->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bulk Actions -->
                <form method="POST" action="{{ route('admin.posts.bulk-action') }}" id="bulk-action-form" class="p-4 border-b border-gray-200 dark:border-gray-700">
                    @csrf
                    <div class="flex items-center space-x-4">
                        <select name="action" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                                   dark:text-gray-300 focus:border-indigo-500
                                                   dark:focus:border-indigo-600 focus:ring-indigo-500
                                                   dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">Bulk Actions</option>
                            <option value="publish">Publish</option>
                            <option value="draft">Move to Draft</option>
                            <option value="archive">Archive</option>
                            <option value="featured">Mark as Featured</option>
                            <option value="unfeatured">Remove Featured</option>
                            <option value="delete">Delete</option>
                        </select>

                        <input type="hidden" name="posts" id="bulk-posts-input">

                        <button type="button" onclick="applyBulkAction()"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Apply
                        </button>

                        <span class="text-sm text-gray-500 dark:text-gray-400" id="selected-count">
                            0 posts selected
                        </span>
                    </div>
                </form>

                <!-- Posts Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Post
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Author
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Categories
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Stats
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Date
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($posts as $post)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" value="{{ $post->id }}"
                                               class="post-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($post->image)
                                                <div class="flex-shrink-0 h-10 w-10 mr-4">
                                                    <img class="h-10 w-10 rounded object-cover"
                                                         src="{{ $post->featured_image }}"
                                                         alt="{{ $post->title }}">
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    <a href="{{ route('admin.posts.show', $post->id) }}"
                                                       class="hover:text-blue-600 dark:hover:text-blue-400">
                                                        {{ Str::limit($post->title, 50) }}
                                                    </a>
                                                </div>
                                                @if($post->excerpt)
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ Str::limit($post->excerpt, 60) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <img class="h-8 w-8 rounded-full"
                                                     src="{{ $post->user->profile_picture }}"
                                                     alt="{{ $post->user->name }}">
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    {{ $post->user->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($post->categories->take(2) as $category)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    {{ $category->name }}
                                                </span>
                                            @endforeach
                                            @if($post->categories->count() > 2)
                                                <span class="text-xs text-gray-500 dark:text-gray-400">+{{ $post->categories->count() - 2 }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="space-y-1">
                                            <span @class([
                                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' => $post->status === 'draft',
                                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $post->status === 'published',
                                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' => $post->status === 'archived'
                                            ])>
                                                {{ ucfirst($post->status) }}
                                            </span>
                                            @if($post->featured)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                    Featured
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex items-center">
                                                <i class="fas fa-eye mr-1"></i>
                                                {{ $post->views }}
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-heart mr-1"></i>
                                                {{ $post->likes_count }}
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-comment mr-1"></i>
                                                {{ $post->comments_count }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        @if($post->published_at)
                                            {{ $post->published_at->format('M d, Y') }}
                                        @else
                                            {{ $post->created_at->format('M d, Y') }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('posts.show', $post->slug) }}"
                                               target="_blank"
                                               class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300"
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.posts.edit', $post) }}"
                                               class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.posts.destroy', $post) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this post?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-newspaper text-4xl mb-4"></i>
                                        <p>No posts found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($posts->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.post-checkbox');
            const selectedCount = document.getElementById('selected-count');

            selectAll.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
                updateSelectedCount();
            });

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            function updateSelectedCount() {
                const selected = document.querySelectorAll('.post-checkbox:checked');
                selectedCount.textContent = `${selected.length} posts selected`;
            }
        });

        function applyBulkAction() {
            const form = document.getElementById('bulk-action-form');
            const action = form.action.value;
            const selected = document.querySelectorAll('.post-checkbox:checked');

            if (!action) {
                alert('Please select a bulk action');
                return;
            }

            if (selected.length === 0) {
                alert('Please select at least one post');
                return;
            }

            const postIds = Array.from(selected).map(checkbox => checkbox.value);
            document.getElementById('bulk-posts-input').value = JSON.stringify(postIds);

            if (action === 'delete') {
                if (!confirm(`Are you sure you want to delete ${selected.length} post(s)?`)) {
                    return;
                }
            }

            form.submit();
        }
    </script>
</x-app-layout>
