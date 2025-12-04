<!-- resources/views/livewire/partials/comment.blade.php -->
<div class="comment" id="comment-{{ $comment->id }}">
    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6 {{ $level > 0 ? 'ml-12' : '' }}">
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

                    <div class="flex items-center space-x-2">
                        <!-- Like Button -->
                        @auth
                            <button wire:click="likeComment({{ $comment->id }})"
                                    class="flex items-center space-x-1 text-sm {{ $comment->likes->contains('user_id', auth()->id()) ? 'text-red-500' : 'text-gray-600 dark:text-gray-300 hover:text-red-500' }}">
                                <i class="{{ $comment->likes->contains('user_id', auth()->id()) ? 'fas' : 'far' }} fa-heart"></i>
                                <span>{{ $comment->likes_count }}</span>
                            </button>
                        @else
                            <div class="flex items-center space-x-1 text-sm text-gray-400">
                                <i class="far fa-heart"></i>
                                <span>{{ $comment->likes_count }}</span>
                            </div>
                        @endauth

                        <!-- Reply Button -->
                        @auth
                            <button wire:click="setReply({{ $comment->id }})"
                                    class="text-sm text-gray-600 dark:text-gray-300 hover:text-blue-500">
                                <i class="fas fa-reply mr-1"></i> Reply
                            </button>
                        @endauth
                    </div>
                </div>

                <div class="comment-content text-gray-700 dark:text-gray-300 mb-4">
                    {!! nl2br(e($comment->content)) !!}
                </div>
            </div>
        </div>

        <!-- Replies -->
        @if($comment->replies->count() > 0)
            <div class="mt-6 space-y-6">
                @foreach($comment->replies as $reply)
                    @include('livewire.partials.comment', ['comment' => $reply, 'level' => $level + 1])
                @endforeach
            </div>
        @endif
    </div>
</div>
