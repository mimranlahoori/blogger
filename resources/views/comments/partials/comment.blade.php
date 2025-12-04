<!-- resources/views/comments/partials/comment.blade.php -->
@php
    // Make sure we have comments
    $comments = $post->comments ?? collect();
@endphp

@if($comments->count() > 0)
    <div class="space-y-6">
        @foreach($comments as $comment)
            <div class="comment" id="comment-{{ $comment->id }}">
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6">
                    <div class="flex items-start mb-4">
                        <img src="{{ $comment->author_avatar }}"
                             alt="{{ $comment->author_name }}"
                             class="w-10 h-10 rounded-full mr-4">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h5 class="font-semibold text-gray-900 dark:text-white">
                                        {{ $comment->author_name }}
                                    </h5>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $comment->created_at->diffForHumans() }}
                                        @if($comment->is_edited)
                                            <span class="text-xs text-gray-400">(edited)</span>
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="comment-content text-gray-700 dark:text-gray-300 mb-4">
                                {!! nl2br(e($comment->content)) !!}
                            </div>

                            <div class="flex items-center space-x-4">
                                <!-- Like Button -->
                                @auth
                                    <form action="{{ route('comments.like', $comment) }}"
                                          method="POST"
                                          class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="flex items-center space-x-1 text-sm text-gray-600 dark:text-gray-300 hover:text-red-500">
                                            <i class="far fa-heart"></i>
                                            <span>{{ $comment->likes_count }}</span>
                                        </button>
                                    </form>
                                @else
                                    <div class="flex items-center space-x-1 text-sm text-gray-400">
                                        <i class="far fa-heart"></i>
                                        <span>{{ $comment->likes_count }}</span>
                                    </div>
                                @endauth

                                <!-- Reply Button -->
                                @auth
                                    <button onclick="showReplyForm({{ $comment->id }})"
                                            class="text-sm text-gray-600 dark:text-gray-300 hover:text-blue-500">
                                        <i class="fas fa-reply mr-1"></i> Reply
                                    </button>
                                @endauth
                            </div>

                            <!-- Reply Form (Hidden by default) -->
                            @auth
                                <div id="reply-form-{{ $comment->id }}" class="mt-4 hidden">
                                    <form action="{{ route('comments.store', $post) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                        <div class="mb-3">
                                            <textarea name="content"
                                                      rows="3"
                                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-white"
                                                      placeholder="Write your reply here..."
                                                      required></textarea>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button type="submit"
                                                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                                                Post Reply
                                            </button>
                                            <button type="button"
                                                    onclick="hideReplyForm({{ $comment->id }})"
                                                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endauth
                        </div>
                    </div>

                    <!-- Replies -->
                    @if($comment->replies && $comment->replies->count() > 0)
                        <div class="ml-12 mt-6 space-y-6">
                            @foreach($comment->replies as $reply)
                                <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-6">
                                    <div class="flex items-start mb-4">
                                        <img src="{{ $reply->author_avatar }}"
                                             alt="{{ $reply->author_name }}"
                                             class="w-8 h-8 rounded-full mr-4">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <div>
                                                    <h6 class="font-medium text-gray-900 dark:text-white">
                                                        {{ $reply->author_name }}
                                                    </h6>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $reply->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="text-gray-700 dark:text-gray-300 mb-4">
                                                {!! nl2br(e($reply->content)) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-12">
        <i class="fas fa-comments text-4xl text-gray-400 mb-4"></i>
        <p class="text-gray-600 dark:text-gray-300">No comments yet. Be the first to comment!</p>
    </div>
@endif

<script>
    function showReplyForm(commentId) {
        document.getElementById('reply-form-' + commentId).classList.remove('hidden');
    }

    function hideReplyForm(commentId) {
        document.getElementById('reply-form-' + commentId).classList.add('hidden');
    }
</script>
