<!-- resources/views/livewire/admin/post-table.blade.php -->
<div>
    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Search posts..."
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-white">
            </div>

            <!-- Status Filter -->
            <div>
                <select wire:model.live="status"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-white">
                    <option value="">All Status</option>
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                </select>
            </div>

            <!-- Bulk Actions -->
            <div>
                <select wire:model="bulkAction"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-white">
                    <option value="">Bulk Actions</option>
                    <option value="publish">Publish</option>
                    <option value="draft">Move to Draft</option>
                    <option value="archive">Archive</option>
                    <option value="delete">Delete</option>
                </select>
            </div>

            <!-- Apply Button -->
            <div>
                <button wire:click="applyBulkAction"
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Apply
                </button>
            </div>
        </div>

        <!-- Selected Count -->
        @if(count($selected) > 0)
            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                {{ count($selected) }} post(s) selected
            </div>
        @endif
    </div>

    <!-- Posts Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox"
                                   wire:model="selectAll"
                                   class="rounded border-gray-300 text-blue-600 shadow-sm">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Post
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Author
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Views
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($posts as $post)
                        <tr>
                            <td class="px-6 py-4">
                                <input type="checkbox"
                                       wire:model="selected"
                                       value="{{ $post->id }}"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($post->image)
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img src="{{ $post->featured_image }}"
                                                 class="h-10 w-10 rounded object-cover">
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            <a href="{{ route('posts.show', $post->slug) }}"
                                               class="hover:text-blue-600 dark:hover:text-blue-400">
                                                {{ $post->title }}
                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ Str::limit($post->excerpt, 50) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $post->user->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span @class([
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' => $post->status === 'draft',
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $post->status === 'published',
                                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' => $post->status === 'archived'
                                ])>
                                    {{ ucfirst($post->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $post->views }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $post->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('posts.show', $post->slug) }}"
                                       class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.posts.edit', $post) }}"
                                       class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button wire:click="deletePost({{ $post->id }})"
                                            onclick="return confirm('Are you sure?')"
                                            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
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

    <!-- No Results -->
    @if($posts->count() === 0)
        <div class="text-center py-12">
            <i class="fas fa-newspaper text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-600 dark:text-gray-300">No posts found.</p>
        </div>
    @endif
</div>
