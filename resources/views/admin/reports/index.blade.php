<!-- resources/views/admin/reports/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Reports') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Filters -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('admin.reports.index') }}"
                           class="inline-flex items-center px-3 py-2 rounded text-sm font-medium {{ !request('status') ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                            All
                        </a>
                        <a href="{{ route('admin.reports.index', ['status' => 'pending']) }}"
                           class="inline-flex items-center px-3 py-2 rounded text-sm font-medium {{ request('status') == 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                            Pending
                        </a>
                        <a href="{{ route('admin.reports.index', ['status' => 'reviewed']) }}"
                           class="inline-flex items-center px-3 py-2 rounded text-sm font-medium {{ request('status') == 'reviewed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                            Reviewed
                        </a>
                        <a href="{{ route('admin.reports.index', ['status' => 'resolved']) }}"
                           class="inline-flex items-center px-3 py-2 rounded text-sm font-medium {{ request('status') == 'resolved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                            Resolved
                        </a>
                        <a href="{{ route('admin.reports.index', ['status' => 'dismissed']) }}"
                           class="inline-flex items-center px-3 py-2 rounded text-sm font-medium {{ request('status') == 'dismissed' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                            Dismissed
                        </a>
                    </div>
                </div>

                <!-- Reports Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Reported Content
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Reporter
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Reason
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
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
                            @forelse($reports as $report)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            @if($report->post)
                                                <a href="{{ route('posts.show', $report->post->slug) }}"
                                                   class="hover:text-blue-600 dark:hover:text-blue-400">
                                                    Post: {{ Str::limit($report->post->title, 30) }}
                                                </a>
                                            @elseif($report->comment)
                                                <span>Comment: {{ Str::limit($report->comment->content, 30) }}</span>
                                            @endif
                                        </div>
                                        @if($report->description)
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                {{ Str::limit($report->description, 50) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ $report->reporter->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $report->reporter->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $report->reason === 'spam' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
                                               ($report->reason === 'harassment' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' :
                                               ($report->reason === 'inappropriate' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                               ($report->reason === 'copyright' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' :
                                               'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'))) }}">
                                            {{ ucfirst($report->reason) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $report->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                               ($report->status === 'reviewed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                               ($report->status === 'resolved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                               'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200')) }}">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                        @if($report->reviewed_at)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $report->reviewed_at->diffForHumans() }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $report->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.reports.show', $report) }}"
                                           class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-3">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @if($report->status === 'pending')
                                            <form action="{{ route('admin.reports.update', $report) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="resolved">
                                                <button type="submit"
                                                        class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">
                                                    <i class="fas fa-check"></i> Resolve
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-flag text-4xl mb-4"></i>
                                        <p>No reports found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($reports->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $reports->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
