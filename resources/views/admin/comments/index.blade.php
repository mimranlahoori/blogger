<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manage Comments') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Filters -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('admin.comments.index') }}"
                           class="inline-flex items-center px-3 py-2 rounded text-sm font-medium {{ !request('status') ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                            All
                        </a>
                        <a href="{{ route('admin.comments.index', ['status' => 'pending']) }}"
                           class="inline-flex items-center px-3 py-2 rounded text-sm font-medium {{ request('status') == 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                            Pending
                        </a>
                        <a href="{{ route('admin.comments.index', ['status' => 'approve']) }}"
                           class="inline-flex items-center px-3 py-2 rounded text-sm font-medium {{ request('status') == 'approve' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                            Approved
                        </a>
                        <a href="{{ route('admin.comments.index', ['status' => 'spam']) }}"
                           class="inline-flex items-center px-3 py-2 rounded text-sm font-medium {{ request('status') == 'spam' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                            Spam
                        </a>
                        <a href="{{ route('admin.comments.index', ['status' => 'trash']) }}"
                           class="inline-flex items-center px-3 py-2 rounded text-sm font-medium {{ request('status') == 'trash' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                            Trash
                        </a>
                    </div>

                    <!-- Search Form -->
                    <form method="GET" class="mt-4">
                        <div class="flex space-x-2">
                            <x-text-input type="text" name="search" placeholder="Search comments..."
                                          class="flex-1" value="{{ request('search') }}" />
                            <x-primary-button type="submit">Search</x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- Bulk Actions -->
                <form method="POST" action="{{ route('admin.comments.bulk-action') }}" id="bulk-action-form" class="p-4 border-b border-gray-200 dark:border-gray-700">
                    @csrf
                    <div class="flex items-center space-x-4">
                        <select name="action" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                                   dark:text-gray-300 focus:border-indigo-500
                                                   dark:focus:border-indigo-600 focus:ring-indigo-500
                                                   dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">Bulk Actions</option>
                            <option value="approve">Approve</option>
                            <option value="spam">Mark as Spam</option>
                            <option value="trash">Move to Trash</option>
                            <option value="delete">Delete Permanently</option>
                        </select>

                        <input type="hidden" name="comments" id="bulk-comments-input">

                        <button type="button" onclick="applyBulkAction()"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Apply
                        </button>

                        <span class="text-sm text-gray-500 dark:text-gray-400" id="selected-count">
                            0 comments selected
                        </span>
                    </div>
                </form>

                <!-- Comments Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Comment
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Author
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Post
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Likes
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
                            @forelse($comments as $comment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" value="{{ $comment->id }}"
                                               class="comment-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ Str::limit($comment->content, 50) }}
                                        </div>
                                        @if($comment->parent_id)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                <i class="fas fa-reply mr-1"></i> Reply to comment #{{ $comment->parent_id }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img class="h-8 w-8 rounded-full mr-2"
                                                 src="{{ $comment->author_avatar }}"
                                                 alt="{{ $comment->author_name }}">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $comment->author_name }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $comment->user ? $comment->user->email : $comment->author_email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            <a href="{{ route('posts.show', $comment->post->slug) }}"
                                               class="hover:text-blue-600 dark:hover:text-blue-400">
                                                {{ Str::limit($comment->post->title, 30) }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $comment->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                               ($comment->status === 'approve' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                               ($comment->status === 'spam' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
                                               'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300')) }}">
                                            {{ ucfirst($comment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $comment->likes_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $comment->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.comments.show', $comment) }}"
                                               class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300"
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.comments.edit', $comment) }}"
                                               class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            @if($comment->status === 'pending')
                                                <form action="{{ route('admin.comments.approve', $comment) }}"
                                                      method="POST"
                                                      class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300"
                                                            title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('admin.comments.spam', $comment) }}"
                                                  method="POST"
                                                  class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                                                        title="Mark as Spam">
                                                    <i class="fas fa-flag"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.comments.destroy', $comment) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this comment?')">
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
                                        <i class="fas fa-comments text-4xl mb-4"></i>
                                        <p>No comments found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($comments->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $comments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.comment-checkbox');
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
                const selected = document.querySelectorAll('.comment-checkbox:checked');
                selectedCount.textContent = `${selected.length} comments selected`;
            }
        });

        function applyBulkAction() {
            const form = document.getElementById('bulk-action-form');
            const action = form.action.value;
            const selected = document.querySelectorAll('.comment-checkbox:checked');

            if (!action) {
                alert('Please select a bulk action');
                return;
            }

            if (selected.length === 0) {
                alert('Please select at least one comment');
                return;
            }

            const commentIds = Array.from(selected).map(checkbox => checkbox.value);
            document.getElementById('bulk-comments-input').value = JSON.stringify(commentIds);

            if (action === 'delete') {
                if (!confirm(`Are you sure you want to delete ${selected.length} comment(s)?`)) {
                    return;
                }
            }

            form.submit();
        }
    </script>
</x-app-layout>
