<!-- resources/views/layouts/navigation.blade.php -->
<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links (Frontend) -->
                @if (!request()->is('admin/*'))
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            {{ __('Home') }}
                        </x-nav-link>
                        <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                            {{ __('Categories') }}
                        </x-nav-link>
                        <x-nav-link :href="route('tags.index')" :active="request()->routeIs('tags.*')">
                            {{ __('Tags') }}
                        </x-nav-link>

                        @auth
                            <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
                                {{ __('Profile') }}
                            </x-nav-link>

                            @can('admin-or-moderator')
                                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                                    {{ __('Admin') }}
                                </x-nav-link>
                            @endcan
                        @endauth
                    </div>
                @endif

                <!-- Navigation Links (Admin) -->
                @if (request()->is('admin/*'))
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            <i class="fas fa-tachometer-alt mr-1"></i> {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.posts.index')" :active="request()->routeIs('admin.posts.*')">
                            <i class="fas fa-newspaper mr-1"></i> {{ __('Posts') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                            <i class="fas fa-folder mr-1"></i> {{ __('Categories') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            <i class="fas fa-users mr-1"></i> {{ __('Users') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.comments.index')" :active="request()->routeIs('admin.comments.*')">
                            <i class="fas fa-comments mr-1"></i> {{ __('Comments') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">
                            <i class="fas fa-flag mr-1"></i> {{ __('Reports') }}
                            @php
                                $pendingReports = \App\Models\Report::pending()->count();
                            @endphp
                            @if ($pendingReports > 0)
                                <span
                                    class="ml-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                    {{ $pendingReports }}
                                </span>
                            @endif
                        </x-nav-link>
                        <x-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')">
                            <i class="fas fa-cog mr-1"></i> {{ __('Settings') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.activity-logs.index')" :active="request()->routeIs('admin.activity-logs.*')">
                            <i class="fas fa-history mr-2"></i> Activity Logs
                        </x-nav-link>
                    </div>

                @endif
            </div>

            <!-- Right Side Of Navbar -->
            <div class="flex items-center space-x-4">
                <!-- Search (Frontend only) -->
                @if (!request()->is('admin/*'))
                    <div class="hidden md:flex items-center">
                        <form action="{{ route('search') }}" method="GET" class="relative">
                            <input type="text" name="q" id="search-input"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white w-64"
                                placeholder="Search..." autocomplete="off">
                            <button type="submit" class="absolute right-2 top-2 text-gray-400 hover:text-blue-500">
                                <i class="fas fa-search"></i>
                            </button>
                            <div id="search-results"
                                class="absolute top-full mt-1 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-lg z-50 hidden">
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                @auth
                                    <div class="flex items-center">
                                        <img src="{{ Auth::user()->profile_picture }}" alt="{{ Auth::user()->name }}"
                                            class="w-8 h-8 rounded-full mr-2">
                                        <div>{{ Auth::user()->name }}</div>
                                    </div>
                                @else
                                    <div>Account</div>
                                @endauth

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @auth
                                <x-dropdown-link :href="route('profile.edit')">
                                    <i class="fas fa-user mr-2"></i> {{ __('Profile') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('profile.bookmarks')">
                                    <i class="fas fa-bookmark mr-2"></i> {{ __('Bookmarks') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('profile.posts')">
                                    <i class="fas fa-newspaper mr-2"></i> {{ __('My Posts') }}
                                </x-dropdown-link>

                                @can('admin-or-moderator')
                                    <div class="border-t border-gray-200 dark:border-gray-700"></div>
                                    <x-dropdown-link :href="route('admin.dashboard')">
                                        <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Admin Dashboard') }}
                                    </x-dropdown-link>
                                @endcan

                                <div class="border-t border-gray-200 dark:border-gray-700"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            @else
                                <x-dropdown-link :href="route('login')">
                                    <i class="fas fa-sign-in-alt mr-2"></i> {{ __('Log in') }}
                                </x-dropdown-link>

                                @if (Route::has('register'))
                                    <x-dropdown-link :href="route('register')">
                                        <i class="fas fa-user-plus mr-2"></i> {{ __('Register') }}
                                    </x-dropdown-link>
                                @endif
                            @endauth
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Mobile menu button -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <!-- Frontend Mobile Links -->
            @if (!request()->is('admin/*'))
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    {{ __('Home') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                    {{ __('Categories') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('tags.index')" :active="request()->routeIs('tags.*')">
                    {{ __('Tags') }}
                </x-responsive-nav-link>

                @auth
                    <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    @can('admin-or-moderator')
                        <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                            {{ __('Admin') }}
                        </x-responsive-nav-link>
                    @endcan
                @endauth
            @endif

            <!-- Admin Mobile Links -->
            @if (request()->is('admin/*'))
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.posts.index')" :active="request()->routeIs('admin.posts.*')">
                    <i class="fas fa-newspaper mr-2"></i> {{ __('Posts') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                    <i class="fas fa-folder mr-2"></i> {{ __('Categories') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    <i class="fas fa-users mr-2"></i> {{ __('Users') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.comments.index')" :active="request()->routeIs('admin.comments.*')">
                    <i class="fas fa-comments mr-2"></i> {{ __('Comments') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">
                    <i class="fas fa-flag mr-2"></i> {{ __('Reports') }}
                    @php
                        $pendingReports = \App\Models\Report::pending()->count();
                    @endphp
                    @if ($pendingReports > 0)
                        <span
                            class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                            {{ $pendingReports }}
                        </span>
                    @endif
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')">
                    <i class="fas fa-cog mr-2"></i> {{ __('Settings') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            @auth
                <div class="px-4">
                    <div class="flex items-center">
                        <img src="{{ Auth::user()->profile_picture }}" alt="{{ Auth::user()->name }}"
                            class="w-10 h-10 rounded-full mr-3">
                        <div>
                            <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                            <div class="text-xs text-gray-400">{{ ucfirst(Auth::user()->role) }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        <i class="fas fa-user mr-2"></i> {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('profile.bookmarks')">
                        <i class="fas fa-bookmark mr-2"></i> {{ __('Bookmarks') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('profile.posts')">
                        <i class="fas fa-newspaper mr-2"></i> {{ __('My Posts') }}
                    </x-responsive-nav-link>

                    @can('admin-or-moderator')
                        <x-responsive-nav-link :href="route('admin.dashboard')">
                            <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Admin Dashboard') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        <i class="fas fa-sign-in-alt mr-2"></i> {{ __('Log in') }}
                    </x-responsive-nav-link>

                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')">
                            <i class="fas fa-user-plus mr-2"></i> {{ __('Register') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>

<!-- Add search autocomplete script -->
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const searchResults = document.getElementById('search-results');

            if (searchInput) {
                let timeout;

                searchInput.addEventListener('input', function() {
                    clearTimeout(timeout);
                    const query = this.value.trim();

                    if (query.length < 2) {
                        searchResults.classList.add('hidden');
                        return;
                    }

                    timeout = setTimeout(() => {
                        fetch(
                                `{{ route('search.autocomplete') }}?query=${encodeURIComponent(query)}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.length > 0) {
                                    let html = '<div class="py-2 max-h-64 overflow-y-auto">';
                                    data.forEach(item => {
                                        html += `
                                        <a href="${item.url}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <div class="flex items-center">
                                                <i class="fas ${item.icon} text-gray-400 mr-3"></i>
                                                <span class="text-gray-700 dark:text-gray-300">${item.title}</span>
                                            </div>
                                        </a>
                                    `;
                                    });
                                    html += '</div>';
                                    searchResults.innerHTML = html;
                                    searchResults.classList.remove('hidden');
                                } else {
                                    searchResults.classList.add('hidden');
                                }
                            })
                            .catch(() => {
                                searchResults.classList.add('hidden');
                            });
                    }, 300);
                });

                // Hide results when clicking outside
                document.addEventListener('click', function(event) {
                    if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
                        searchResults.classList.add('hidden');
                    }
                });
            }
        });
    </script>
@endpush
