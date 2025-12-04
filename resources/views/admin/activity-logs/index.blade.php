<!-- resources/views/admin/activity-logs/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Activity Logs') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Track all system activities</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Filters</h3>

                    <form method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- User Filter -->
                            <div>
                                <x-input-label for="user_id" :value="__('User')" />
                                <select id="user_id" name="user_id"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                               dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600
                                               focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">All Users</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Action Filter -->
                            <div>
                                <x-input-label for="action" :value="__('Action')" />
                                <x-text-input id="action" name="action" type="text" class="mt-1 block w-full"
                                              placeholder="Search by action..."
                                              value="{{ request('action') }}" />
                            </div>

                            <!-- Date From -->
                            <div>
                                <x-input-label for="date_from" :value="__('Date From')" />
                                <x-text-input id="date_from" name="date_from" type="date" class="mt-1 block w-full"
                                              value="{{ request('date_from') }}" />
                                              <x-input-error :messages="$errors->get('date_from')" class="mt-2" />
                            </div>

                            <!-- Date To -->
                            <div>
                                <x-input-label for="date_to" :value="__('Date To')" />
                                <x-text-input id="date_to" name="date_to" type="date" class="mt-1 block w-full"
                                              value="{{ request('date_to') }}" />
                                <x-input-error :messages="$errors->get('date_to')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Search -->
                        <div>
                            <x-input-label for="search" :value="__('Search')" />
                            <x-text-input id="search" name="search" type="text" class="mt-1 block w-full"
                                          placeholder="Search in logs..."
                                          value="{{ request('search') }}" />
                                          <x-input-error :messages="$errors->get('search')" class="mt-2" />
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            <x-primary-button type="submit">
                                <i class="fas fa-filter mr-2"></i> Apply Filters
                            </x-primary-button>
                            <a href="{{ route('admin.activity-logs.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Logs Table Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <!-- Header with Clear Logs Button -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Activity Logs</h3>

                        <div class="flex items-center space-x-2">
                            <!-- Clear Logs Form -->
                            <form method="POST" action="{{ route('admin.activity-logs.clear') }}"
                                  class="inline"
                                  onsubmit="return confirm('Are you sure you want to clear old logs?')">
                                @csrf
                                <div class="flex items-center space-x-2">
                                    <select name="older_than"
                                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-800
                                                   dark:text-gray-300 focus:border-indigo-500
                                                   dark:focus:border-indigo-600 focus:ring-indigo-500
                                                   dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                                        <option value="">Clear all logs</option>
                                        <option value="7">Older than 7 days</option>
                                        <option value="30">Older than 30 days</option>
                                        <option value="90">Older than 90 days</option>
                                        <option value="365">Older than 1 year</option>
                                    </select>
                                    <button type="submit"
                                            class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                        <i class="fas fa-trash mr-1"></i> Clear Logs
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Logs Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    User
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Action
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Description
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    IP Address
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Date
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($logs as $log)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($log->user)
                                            <div class="flex items-center">
                                                <img class="h-8 w-8 rounded-full mr-2"
                                                     src="{{ $log->user->profile_picture }}"
                                                     alt="{{ $log->user->name }}">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $log->user->name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $log->user->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">System</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ str_contains($log->action, 'created') ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                               (str_contains($log->action, 'updated') ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                               (str_contains($log->action, 'deleted') ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
                                               'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300')) }}">
                                            {{ str_replace('_', ' ', $log->action) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $log->description }}
                                        </div>
                                        @if($log->metadata)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                <details>
                                                    <summary class="cursor-pointer hover:text-gray-700 dark:hover:text-gray-300">
                                                        View Details
                                                    </summary>
                                                    <pre class="mt-2 text-xs bg-gray-50 dark:bg-gray-900 p-2 rounded overflow-auto max-h-32">{{ is_string($log->metadata) ? json_encode(json_decode($log->metadata, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : json_encode($log->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                </details>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $log->ip_address }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <div>{{ $log->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs">{{ $log->created_at->format('h:i A') }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="text-gray-400 dark:text-gray-500">
                                            <i class="fas fa-history text-4xl mb-4"></i>
                                            <p class="text-lg font-medium text-gray-900 dark:text-white mb-2">No activity logs found</p>
                                            <p class="text-gray-600 dark:text-gray-400">System activities will appear here</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($logs->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700 dark:text-gray-400">
                                Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} logs
                            </div>
                            <div>
                                {{ $logs->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
