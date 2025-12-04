<!-- resources/views/admin/users/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manage Users') }}
            </h2>
            <a href="{{ route('admin.users.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-plus mr-2"></i> New User
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Filters -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <form method="GET" class="space-y-4 md:space-y-0 md:flex md:space-x-4">
                        <div class="flex-1">
                            <x-text-input type="text" name="search" placeholder="Search users..."
                                          class="w-full" value="{{ request('search') }}" />
                        </div>

                        <div>
                            <select name="role" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                                   dark:text-gray-300 focus:border-indigo-500
                                                   dark:focus:border-indigo-600 focus:ring-indigo-500
                                                   dark:focus:ring-indigo-600 rounded-md shadow-sm w-full">
                                <option value="">All Roles</option>
                                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="moderator" {{ request('role') == 'moderator' ? 'selected' : '' }}>Moderator</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>

                        <div>
                            <select name="status" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                                   dark:text-gray-300 focus:border-indigo-500
                                                   dark:focus:border-indigo-600 focus:ring-indigo-500
                                                   dark:focus:ring-indigo-600 rounded-md shadow-sm w-full">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div>
                            <x-primary-button type="submit">Filter</x-primary-button>
                            <a href="{{ route('admin.users.index') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Bulk Actions -->
                <form method="POST" action="{{ route('admin.users.bulk-action') }}" id="bulk-action-form" class="p-4 border-b border-gray-200 dark:border-gray-700">
                    @csrf
                    <div class="flex items-center space-x-4">
                        <select name="action" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                                   dark:text-gray-300 focus:border-indigo-500
                                                   dark:focus:border-indigo-600 focus:ring-indigo-500
                                                   dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">Bulk Actions</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="delete">Delete</option>
                        </select>

                        <input type="hidden" name="users" id="bulk-users-input">

                        <button type="button" onclick="applyBulkAction()"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Apply
                        </button>

                        <span class="text-sm text-gray-500 dark:text-gray-400" id="selected-count">
                            0 users selected
                        </span>
                    </div>
                </form>

                <!-- Users Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    User
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Role
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Posts
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Joined
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->id !== auth()->id())
                                            <input type="checkbox" value="{{ $user->id }}"
                                                   class="user-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full object-cover"
                                                     src="{{ $user->profile_picture }}"
                                                     alt="{{ $user->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    <a href="{{ route('profile.show.public', $user->name) }}"
                                                       class="hover:text-blue-600 dark:hover:text-blue-400">
                                                        {{ $user->name }}
                                                    </a>
                                                </div>
                                                @if($user->phone)
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $user->phone }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $user->role === 'admin' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
                                               ($user->role === 'moderator' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                               'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                Inactive
                                            </span>
                                        @endif
                                        @if($user->email_verified)
                                            <span class="ml-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                Verified
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $user->posts()->count() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                               class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300"
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            @if($user->id !== auth()->id())
                                                @if($user->is_active)
                                                    <form action="{{ route('admin.users.toggle-active', $user) }}"
                                                          method="POST"
                                                          class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300"
                                                                title="Deactivate">
                                                            <i class="fas fa-user-slash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.users.toggle-active', $user) }}"
                                                          method="POST"
                                                          class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300"
                                                                title="Activate">
                                                            <i class="fas fa-user-check"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('admin.users.destroy', $user) }}"
                                                      method="POST"
                                                      class="inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-users text-4xl mb-4"></i>
                                        <p>No users found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.user-checkbox');
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
                const selected = document.querySelectorAll('.user-checkbox:checked');
                selectedCount.textContent = `${selected.length} users selected`;
            }
        });

        function applyBulkAction() {
            const form = document.getElementById('bulk-action-form');
            const action = form.action.value;
            const selected = document.querySelectorAll('.user-checkbox:checked');

            if (!action) {
                alert('Please select a bulk action');
                return;
            }

            if (selected.length === 0) {
                alert('Please select at least one user');
                return;
            }

            const userIds = Array.from(selected).map(checkbox => checkbox.value);
            document.getElementById('bulk-users-input').value = JSON.stringify(userIds);

            if (action === 'delete') {
                if (!confirm(`Are you sure you want to delete ${selected.length} user(s)?`)) {
                    return;
                }
            }

            form.submit();
        }
    </script>
</x-app-layout>
