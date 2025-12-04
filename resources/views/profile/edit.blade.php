<!-- resources/views/profile/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center space-x-4 mb-6">
                                <img src="{{ $user->profile_picture }}"
                                     alt="{{ $user->name }}"
                                     class="w-16 h-16 rounded-full">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>

                            <nav class="space-y-1">
                                <a href="{{ route('profile.edit') }}"
                                   class="flex items-center px-3 py-2 text-sm font-medium rounded-md bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200">
                                    <i class="fas fa-user mr-3"></i>
                                    Profile Information
                                </a>
                                <a href="{{ route('profile.bookmarks') }}"
                                   class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-bookmark mr-3"></i>
                                    Bookmarks
                                </a>
                                <a href="{{ route('profile.posts') }}"
                                   class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-newspaper mr-3"></i>
                                    My Posts
                                </a>
                                <a href="{{ route('profile.followers') }}"
                                   class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-users mr-3"></i>
                                    Followers
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Profile Information Form -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-8">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Profile Information</h3>
                        </div>

                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="p-6">
                            @csrf
                            @method('patch')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <x-input-label for="name" :value="__('Name')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                                  :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <!-- Email -->
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                                  :value="old('email', $user->email)" required autocomplete="email" />
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>

                                <!-- Phone -->
                                <div>
                                    <x-input-label for="phone" :value="__('Phone Number')" />
                                    <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full"
                                                  :value="old('phone', $user->phone)" autocomplete="tel" />
                                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                                </div>

                                <!-- Profile Picture -->
                                <div>
                                    <x-input-label for="picture" :value="__('Profile Picture')" />
                                    <input id="picture" name="picture" type="file"
                                           class="mt-1 block w-full text-sm text-gray-500
                                                  file:mr-4 file:py-2 file:px-4
                                                  file:rounded-full file:border-0
                                                  file:text-sm file:font-semibold
                                                  file:bg-blue-50 file:text-blue-700
                                                  hover:file:bg-blue-100
                                                  dark:file:bg-blue-900 dark:file:text-blue-200
                                                  dark:hover:file:bg-blue-800"
                                           accept="image/*">
                                    <x-input-error class="mt-2" :messages="$errors->get('picture')" />
                                    @if($user->picture && $user->picture !== 'default.png')
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                            Current: {{ $user->picture }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Bio -->
                                <div class="md:col-span-2">
                                    <x-input-label for="bio" :value="__('Bio')" />
                                    <textarea id="bio" name="bio" rows="3"
                                              class="mt-1 block w-full border-gray-300 dark:border-gray-700
                                                     dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500
                                                     dark:focus:border-indigo-600 focus:ring-indigo-500
                                                     dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        {{ old('bio', $user->bio) }}
                                    </textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                                </div>

                                <!-- Website -->
                                <div>
                                    <x-input-label for="website" :value="__('Website')" />
                                    <x-text-input id="website" name="website" type="url" class="mt-1 block w-full"
                                                  :value="old('website', $user->website)" autocomplete="url" />
                                    <x-input-error class="mt-2" :messages="$errors->get('website')" />
                                </div>

                                <!-- Social Media -->
                                <div>
                                    <x-input-label for="facebook_url" :value="__('Facebook')" />
                                    <x-text-input id="facebook_url" name="facebook_url" type="url" class="mt-1 block w-full"
                                                  :value="old('facebook_url', $user->facebook_url)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('facebook_url')" />
                                </div>

                                <div>
                                    <x-input-label for="twitter_url" :value="__('Twitter')" />
                                    <x-text-input id="twitter_url" name="twitter_url" type="url" class="mt-1 block w-full"
                                                  :value="old('twitter_url', $user->twitter_url)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('twitter_url')" />
                                </div>

                                <div>
                                    <x-input-label for="instagram_url" :value="__('Instagram')" />
                                    <x-text-input id="instagram_url" name="instagram_url" type="url" class="mt-1 block w-full"
                                                  :value="old('instagram_url', $user->instagram_url)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('instagram_url')" />
                                </div>
                            </div>

                            <div class="flex items-center gap-4 mt-6">
                                <x-primary-button>{{ __('Save Changes') }}</x-primary-button>

                                @if (session('status') === 'profile-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                       class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('Saved.') }}
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Password Update Form -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-8">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Update Password</h3>
                        </div>

                        <form method="POST" action="{{ route('password.update') }}" class="p-6">
                            @csrf
                            @method('patch')

                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="current_password" :value="__('Current Password')" />
                                    <x-text-input id="current_password" name="current_password" type="password"
                                                  class="mt-1 block w-full" autocomplete="current-password" />
                                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="password" :value="__('New Password')" />
                                    <x-text-input id="password" name="password" type="password"
                                                  class="mt-1 block w-full" autocomplete="new-password" />
                                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                    <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                                                  class="mt-1 block w-full" autocomplete="new-password" />
                                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                                </div>

                                <div class="flex items-center gap-4">
                                    <x-primary-button>{{ __('Update Password') }}</x-primary-button>

                                    @if (session('status') === 'password-updated')
                                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                           class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ __('Password updated.') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Notification Settings -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Notification Settings</h3>
                        </div>

                        <form method="POST" action="{{ route('profile.notifications.update') }}" class="p-6">
                            @csrf

                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label for="email_new_comment" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Email when someone comments on my posts
                                        </label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Receive email notifications for new comments</p>
                                    </div>
                                    <input id="email_new_comment" name="email_new_comment" type="checkbox"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm
                                                  focus:border-blue-300 focus:ring focus:ring-blue-200
                                                  focus:ring-opacity-50"
                                           {{ $notificationSettings->email_new_comment ? 'checked' : '' }}>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label for="email_comment_reply" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Email when someone replies to my comments
                                        </label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Receive email notifications for comment replies</p>
                                    </div>
                                    <input id="email_comment_reply" name="email_comment_reply" type="checkbox"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm
                                                  focus:border-blue-300 focus:ring focus:ring-blue-200
                                                  focus:ring-opacity-50"
                                           {{ $notificationSettings->email_comment_reply ? 'checked' : '' }}>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label for="email_post_like" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Email when someone likes my posts
                                        </label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Receive email notifications for post likes</p>
                                    </div>
                                    <input id="email_post_like" name="email_post_like" type="checkbox"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm
                                                  focus:border-blue-300 focus:ring focus:ring-blue-200
                                                  focus:ring-opacity-50"
                                           {{ $notificationSettings->email_post_like ? 'checked' : '' }}>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label for="email_new_follower" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Email when someone follows me
                                        </label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Receive email notifications for new followers</p>
                                    </div>
                                    <input id="email_new_follower" name="email_new_follower" type="checkbox"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm
                                                  focus:border-blue-300 focus:ring focus:ring-blue-200
                                                  focus:ring-opacity-50"
                                           {{ $notificationSettings->email_new_follower ? 'checked' : '' }}>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label for="email_newsletter" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Newsletter
                                        </label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Receive weekly newsletter with featured posts</p>
                                    </div>
                                    <input id="email_newsletter" name="email_newsletter" type="checkbox"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm
                                                  focus:border-blue-300 focus:ring focus:ring-blue-200
                                                  focus:ring-opacity-50"
                                           {{ $notificationSettings->email_newsletter ? 'checked' : '' }}>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label for="push_notifications" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Push Notifications
                                        </label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Receive browser push notifications</p>
                                    </div>
                                    <input id="push_notifications" name="push_notifications" type="checkbox"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm
                                                  focus:border-blue-300 focus:ring focus:ring-blue-200
                                                  focus:ring-opacity-50"
                                           {{ $notificationSettings->push_notifications ? 'checked' : '' }}>
                                </div>
                            </div>

                            <div class="mt-6">
                                <x-primary-button>{{ __('Save Notification Settings') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
