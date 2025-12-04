<div>
    @auth
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                @if ($parentId)
                    Reply to Comment
                @else
                    Add a Comment
                @endif
                </h4>

            <form wire:submit.prevent="submitComment">
                <div class="mb-4">

                    <textarea wire:model="content"                               rows="4"                              
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-white"
                                                      placeholder="Write your comment here..."                               required></textarea>
                    @error('content')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                </div>

                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ Str::length($content) }}/1000 characters
                        </div>

                    <div class="flex items-center space-x-2">
                        @if ($parentId)
                            <button type="button"                                     wire:click="$set('parentId', null)"

                                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                                Cancel Reply
                                </button>
                        @endif

                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            @if ($parentId)
                                Post Reply
                            @else
                                Post Comment
                            @endif
                            </button>
                        </div>
                    </div>
                </form>
            </div>
    @endauth {{-- <--- MISSING TAG ADDED HERE --}}

    @guest
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6 text-center">
            <p class="text-gray-600 dark:text-gray-300 mb-4">
                Please <a href="{{ route('login') }}"
                    class="text-blue-600 dark:text-blue-400 hover:underline">login</a>
                to leave a comment.
                </p>
            </div>
    @endguest

    <div class="space-y-6">
        @forelse($comments as $comment)
            @include('livewire.partials.comment', ['comment' => $comment, 'level' => 0])
        @empty
            <div class="text-center py-12">
                <i class="fas fa-comments text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600 dark:text-gray-300">No comments yet. Be the first to comment!
                </p>
                </div>
        @endforelse
        </div>
</div>

@push('scripts')
    <script>
        Livewire.on('scroll-to-comment-form', () => {
            document.querySelector('textarea').focus();
            window.scrollTo({
                top: document.querySelector('textarea').offsetTop - 100,
                behavior: 'smooth'
            });
        });
    </script>
@endpush
