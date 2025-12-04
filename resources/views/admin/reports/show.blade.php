<!-- resources/views/admin/reports/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Report Details') }}
            </h2>
            <a href="{{ route('admin.reports.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to Reports
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Report Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-8">
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Report Information</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Report ID</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $report->id }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $report->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                               ($report->status === 'reviewed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                               ($report->status === 'resolved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                               'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200')) }}">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Reason</label>
                                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $report->reason === 'spam' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
                                               ($report->reason === 'harassment' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' :
                                               ($report->reason === 'inappropriate' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                               ($report->reason === 'copyright' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' :
                                               'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'))) }}">
                                            {{ ucfirst($report->reason) }}
                                        </span>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Reported At</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                            {{ $report->created_at->format('F d, Y \a\t h:i A') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Reported Content -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Reported Content</h3>

                                @if($report->post)
                                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6">
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">Post</h4>
                                        <a href="{{ route('posts.show', $report->post->slug) }}"
                                           class="text-blue-600 dark:text-blue-400 hover:underline">
                                            {{ $report->post->title }}
                                        </a>
                                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                            {{ Str::limit($report->post->excerpt, 100) }}
                                        </p>
                                    </div>
                                @elseif($report->comment)
                                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6">
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">Comment</h4>
                                        <div class="text-gray-700 dark:text-gray-300 mb-4">
                                            {{ $report->comment->content }}
                                        </div>
                                        @if($report->comment->post)
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                On post:
                                                <a href="{{ route('posts.show', $report->comment->post->slug) }}"
                                                   class="text-blue-600 dark:text-blue-400 hover:underline">
                                                    {{ $report->comment->post->title }}
                                                </a>
                                            </p>
                                        @endif
                                    </div>
                                @endif

                                @if($report->description)
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                                            Reporter's Description
                                        </label>
                                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                                            <p class="text-gray-700 dark:text-gray-300">{{ $report->description }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Admin Actions -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Update Report Status</h3>

                                <form action="{{ route('admin.reports.update', $report) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Status
                                            </label>
                                            <select name="status"
                                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-white">
                                                <option value="pending" {{ $report->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="reviewed" {{ $report->status === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                                <option value="resolved" {{ $report->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                                <option value="dismissed" {{ $report->status === 'dismissed' ? 'selected' : '' }}>Dismissed</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Admin Notes (Optional)
                                            </label>
                                            <textarea name="admin_notes"
                                                      rows="3"
                                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-white"
                                                      placeholder="Add any notes about this report...">{{ old('admin_notes', $report->admin_notes) }}</textarea>
                                        </div>

                                        <div class="flex items-center gap-4">
                                            <x-primary-button>
                                                Update Report
                                            </x-primary-button>
                                            @if($report->post)
                                                <a href="{{ route('admin.posts.edit', $report->post) }}"
                                                   class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    Edit Post
                                                </a>
                                            @endif
                                            @if($report->comment)
                                                <a href="{{ route('admin.comments.edit', $report->comment) }}"
                                                   class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    Edit Comment
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Reporter Info -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Reporter</h3>

                            <div class="flex items-center mb-4">
                                <img src="{{ $report->reporter->profile_picture }}"
                                     alt="{{ $report->reporter->name }}"
                                     class="w-12 h-12 rounded-full mr-4">
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $report->reporter->name }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $report->reporter->email }}</p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Role</label>
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ ucfirst($report->reporter->role) }}
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Joined</label>
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ $report->reporter->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Author Info -->
                    @if($report->post && $report->post->user)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Content Author</h3>

                                <div class="flex items-center mb-4">
                                    <img src="{{ $report->post->user->profile_picture }}"
                                         alt="{{ $report->post->user->name }}"
                                         class="w-12 h-12 rounded-full mr-4">
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white">{{ $report->post->user->name }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $report->post->user->email }}</p>
                                    </div>
                                </div>

                                <a href="{{ route('admin.users.edit', $report->post->user) }}"
                                   class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full justify-center">
                                    View User Profile
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
