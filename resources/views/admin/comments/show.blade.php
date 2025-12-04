<!-- resources/views/comments/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Comment Details') }}
            </h2>
            <a href="{{ route('admin.comments.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to Comments
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Comment Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                        <div class="p-8">
                            <!-- Comment Header -->
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center">
                                    <img src="{{ $comment->author_avatar }}"
                                         alt="{{ $comment->author_name }}"
                                         class="w-12 h-12 rounded-full mr-4">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                            {{ $comment->author_name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $comment->created_at->format('F d, Y \a\t h:i A') }}
                                            @if($comment->is_edited)
                                                <span class="text-gray-400">(edited)</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $comment->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                           ($comment->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                           ($comment->status === 'spam' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
                                           'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300')) }}">
                                        {{ ucfirst($comment->status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Comment Content -->
                            <div class="mb-8">
                                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6">
                                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">
                                        {{ $comment->content }}
                                    </p>
                                </div>
                            </div>

                            <!-- Comment Stats -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 text-center">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ $comment->likes_count }}
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Likes</div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 text-center">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ $comment->replies_count }}
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Replies</div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 text-center">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ $comment->reported_count }}
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Reports</div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 text-center">
                                    <div class="text-lg font-bold text-gray-900 dark:text-white">
                                        @if($comment->user)
                                            {{ $comment->user->posts()->count() }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">User Posts</div>
                                </div>
                            </div>

                            <!-- Post Info -->
                            <div class="mb-8">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">On Post</h4>
                                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                                    <h5 class="font-medium text-gray-900 dark:text-white mb-2">
                                        <a href="{{ route('posts.show', $comment->post->slug) }}"
                                           class="hover:text-blue-600 dark:hover:text-blue-400">
                                            {{ $comment->post->title }}
                                        </a>
                                    </h5>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Posted by {{ $comment->post->user->name }} •
                                        {{ $comment->post->created_at->format('M d, Y') }} •
                                        {{ $comment->post->comments_count }} comments
                                    </p>
                                </div>
                            </div>

                            <!-- Actions -->
                            @auth
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('posts.show', $comment->post->slug) . '#comment-' . $comment->id }}"
                                       target="_blank"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        <i class="fas fa-external-link-alt mr-2"></i> View in Post
                                    </a>

                                    @can('update', $comment)
                                        <a href="{{ route('admin.comments.edit', $comment) }}"
                                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                            <i class="fas fa-edit mr-2"></i> Edit Comment
                                        </a>
                                    @endcan

                                    @if(auth()->id() !== $comment->user_id)
                                        <button onclick="showReportForm()"
                                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                            <i class="fas fa-flag mr-2"></i> Report
                                        </button>
                                    @endif
                                </div>

                                <!-- Report Form (Hidden) -->

                            @endauth
                        </div>
                    </div>

                    <!-- Replies Section -->
                    @if($comment->replies->count() > 0)
                        <div class="mt-8">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                        Replies ({{ $comment->replies_count }})
                                    </h3>
                                </div>
                                <div class="p-6">
                                    <div class="space-y-6">
                                        @foreach($comment->replies as $reply)
                                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                                <div class="flex items-center justify-between mb-2">
                                                    <div class="flex items-center">
                                                        <img src="{{ $reply->author_avatar }}"
                                                             alt="{{ $reply->author_name }}"
                                                             class="w-8 h-8 rounded-full mr-2">
                                                        <span class="font-medium text-gray-900 dark:text-white">
                                                            {{ $reply->author_name }}
                                                        </span>
                                                    </div>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $reply->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <p class="text-gray-700 dark:text-gray-300 mb-3">
                                                    {{ $reply->content }}
                                                </p>
                                                <div class="flex items-center justify-between">
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $reply->likes_count }} likes
                                                    </div>
                                                    <a href="{{ route('comments.show', $reply) }}"
                                                       class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                                        View Details
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    @if($comment->replies_count > 5)
                                        <div class="mt-4 text-center">
                                            <a href="{{ route('comments.replies', $comment) }}"
                                               class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600">
                                                View all replies
                                                <i class="fas fa-arrow-right ml-2"></i>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Author Info -->
                    @if($comment->user)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Author</h3>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center mb-4">
                                    <img src="{{ $comment->user->profile_picture }}"
                                         alt="{{ $comment->user->name }}"
                                         class="w-12 h-12 rounded-full mr-4">
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white">{{ $comment->user->name }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $comment->user->email }}</p>
                                    </div>
                                </div>

                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-tag text-gray-400 mr-2"></i>
                                        <span class="capitalize">{{ $comment->user->role }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-comments text-gray-400 mr-2"></i>
                                        <span>{{ $comment->user->comments()->count() }} comments</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar text-gray-400 mr-2"></i>
                                        <span>Joined {{ $comment->user->created_at->format('M Y') }}</span>
                                    </div>
                                </div>

                                @if($comment->user->bio)
                                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $comment->user->bio }}</p>
                                    </div>
                                @endif

                                <div class="mt-4">
                                    <a href="{{ route('profile.show.public', $comment->user->name) }}"
                                       class="inline-flex items-center justify-center w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600">
                                        <i class="fas fa-user mr-2"></i> View Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Guest Comment</h3>
                            </div>
                            <div class="p-6">
                                <p class="text-gray-600 dark:text-gray-400 mb-4">
                                    This comment was posted by a guest user.
                                </p>
                                <div class="text-sm space-y-2">
                                    @if($comment->author_email)
                                        <div class="flex items-center">
                                            <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                            <span>{{ $comment->author_email }}</span>
                                        </div>
                                    @endif
                                    @if($comment->author_website)
                                        <div class="flex items-center">
                                            <i class="fas fa-globe text-gray-400 mr-2"></i>
                                            <a href="{{ $comment->author_website }}"
                                               target="_blank"
                                               class="text-blue-600 dark:text-blue-400 hover:underline">
                                                {{ $comment->author_website }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Comment History -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">History</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Created</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $comment->created_at->format('M d, Y h:i A') }}
                                    </div>
                                </div>

                                @if($comment->is_edited)
                                    <div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Last Edited</div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $comment->edited_at->format('M d, Y h:i A') }}
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">IP Address</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $comment->ip_address ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>

                            @if($comment->parent_id)
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">Reply To</div>
                                    <a href="{{ route('comments.show', $comment->parent_id) }}"
                                       class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800">
                                        View Parent Comment
                                        <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showReportForm() {
            document.getElementById('report-form').classList.remove('hidden');
        }

        function hideReportForm() {
            document.getElementById('report-form').classList.add('hidden');
        }
    </script>
</x-app-layout>
