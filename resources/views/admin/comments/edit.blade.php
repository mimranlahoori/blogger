<!-- resources/views/comments/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Comment') }}
            </h2>
            <a href="{{ route('admin.comments.show', $comment) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to Comment
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <form method="POST" action="{{ route('admin.comments.update', $comment) }}">
                    @csrf
                    @method('PUT')

                    <div class="p-8">
                        <!-- Comment Info -->
                        <div class="mb-6">
                            <div class="flex items-center mb-4">
                                <img src="{{ $comment->author_avatar }}"
                                     alt="{{ $comment->author_name }}"
                                     class="w-10 h-10 rounded-full mr-3">
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $comment->author_name }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        On post:
                                        <a href="{{ route('posts.show', $comment->post->slug) }}"
                                           class="text-blue-600 dark:text-blue-400 hover:underline">
                                            {{ $comment->post->title }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="mb-6">
                            <x-input-label for="content" :value="__('Comment Content')" />
                            <textarea id="content" name="content" rows="6"
                                      class="mt-1 block w-full border-gray-300 dark:border-gray-700
                                             dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500
                                             dark:focus:border-indigo-600 focus:ring-indigo-500
                                             dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                      required>{{ old('content', $comment->content) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('content')" />
                            <div class="mt-2 flex justify-between text-sm text-gray-500 dark:text-gray-400">
                                <span>Make sure your comment follows community guidelines</span>
                                <span id="char-count">{{ strlen(old('content', $comment->content)) }}/1000 characters</span>
                            </div>
                        </div>

                        <!-- Status (Admin only) -->
                        @can('admin-or-moderator')
                        <div class="mb-6">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700
                                           dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500
                                           dark:focus:border-indigo-600 focus:ring-indigo-500
                                           dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="pending" {{ old('status', $comment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approve" {{ old('status', $comment->status) == 'approve' ? 'selected' : '' }}>Approved</option>
                                <option value="spam" {{ old('status', $comment->status) == 'spam' ? 'selected' : '' }}>Spam</option>
                                <option value="trash" {{ old('status', $comment->status) == 'trash' ? 'selected' : '' }}>Trash</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>
                        @endcan

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                @if($comment->is_edited)
                                    <div class="flex items-center">
                                        <i class="fas fa-edit mr-1"></i>
                                        <span>Last edited: {{ $comment->edited_at ? $comment->edited_at->diffForHumans() : $comment->updated_at->diffForHumans() }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        <span>Created: {{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center space-x-2">
                                <a href="{{ route('posts.show', $comment->post->slug) }}#comment-{{ $comment->id }}"
                                   class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                                    Cancel
                                </a>
                                <x-primary-button>
                                    <i class="fas fa-save mr-2"></i> Update Comment
                                </x-primary-button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Danger Zone -->
            @can('delete', $comment)
                <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow border border-red-200 dark:border-red-900">
                    <div class="px-6 py-4 border-b border-red-200 dark:border-red-900 bg-red-50 dark:bg-red-900/20">
                        <h3 class="text-lg font-medium text-red-800 dark:text-red-200">Danger Zone</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Delete this comment</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Once deleted, this comment cannot be recovered. This will also delete all replies to this comment.
                                </p>
                            </div>
                            <form action="{{ route('admin.comments.destroy', $comment) }}"
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this comment? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <i class="fas fa-trash mr-1"></i> Delete Comment
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan

            <!-- Comment Preview -->
            <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Preview</h3>
                </div>
                <div class="p-6">
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <img src="{{ $comment->author_avatar }}"
                                 alt="{{ $comment->author_name }}"
                                 class="w-8 h-8 rounded-full mr-3">
                            <div>
                                <h5 class="font-medium text-gray-900 dark:text-white">{{ $comment->author_name }}</h5>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Preview - Updated just now
                                </p>
                            </div>
                        </div>
                        <div id="comment-preview" class="text-gray-700 dark:text-gray-300">
                            {{ old('content', $comment->content) }}
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center">
                                    <i class="fas fa-heart mr-1"></i>
                                    <span>{{ $comment->likes_count }} likes</span>
                                </div>
                                @if($comment->replies_count > 0)
                                    <div class="flex items-center">
                                        <i class="fas fa-reply mr-1"></i>
                                        <span>{{ $comment->replies_count }} replies</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const contentTextarea = document.getElementById('content');
            const charCount = document.getElementById('char-count');
            const commentPreview = document.getElementById('comment-preview');

            // Update character count
            contentTextarea.addEventListener('input', function() {
                const length = this.value.length;
                charCount.textContent = length + '/1000 characters';

                // Update preview
                commentPreview.textContent = this.value;

                // Add warning for long comments
                if (length > 900) {
                    charCount.classList.add('text-yellow-600', 'font-medium');
                } else {
                    charCount.classList.remove('text-yellow-600', 'font-medium');
                }

                if (length > 1000) {
                    charCount.classList.remove('text-yellow-600');
                    charCount.classList.add('text-red-600', 'font-bold');
                } else {
                    charCount.classList.remove('text-red-600', 'font-bold');
                }
            });

            // Initialize character count
            const initialLength = contentTextarea.value.length;
            charCount.textContent = initialLength + '/1000 characters';

            if (initialLength > 900) {
                charCount.classList.add('text-yellow-600', 'font-medium');
            }

            if (initialLength > 1000) {
                charCount.classList.remove('text-yellow-600');
                charCount.classList.add('text-red-600', 'font-bold');
            }
        });
    </script>
    @endpush
</x-app-layout>
