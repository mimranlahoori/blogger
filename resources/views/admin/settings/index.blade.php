<!-- resources/views/admin/settings/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Site Settings') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf

                <div class="space-y-8">
                    <!-- General Settings -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">General Settings</h3>
                        </div>

                        <div class="p-6 space-y-6">
                            @foreach($settings['general'] ?? [] as $setting)
                                <div>
                                    <label for="{{ $setting->setting_key }}"
                                           class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ ucwords(str_replace('_', ' ', $setting->setting_key)) }}
                                    </label>

                                    @if($setting->setting_type === 'boolean')
                                        <div class="flex items-center">
                                            <input id="{{ $setting->setting_key }}"
                                                   name="{{ $setting->setting_key }}"
                                                   type="checkbox"
                                                   class="rounded border-gray-300 text-blue-600 shadow-sm
                                                          focus:border-blue-300 focus:ring focus:ring-blue-200
                                                          focus:ring-opacity-50"
                                                   {{ $setting->setting_value === 'true' ? 'checked' : '' }}>
                                            <label for="{{ $setting->setting_key }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $setting->setting_value === 'true' ? 'Enabled' : 'Disabled' }}
                                            </label>
                                        </div>
                                    @elseif($setting->setting_type === 'number')
                                        <x-text-input id="{{ $setting->setting_key }}"
                                                      name="{{ $setting->setting_key }}"
                                                      type="number"
                                                      class="mt-1 block w-full"
                                                      :value="$setting->setting_value" />
                                    @else
                                        <x-text-input id="{{ $setting->setting_key }}"
                                                      name="{{ $setting->setting_key }}"
                                                      type="text"
                                                      class="mt-1 block w-full"
                                                      :value="$setting->setting_value" />
                                    @endif

                                    @if($setting->setting_key === 'posts_per_page')
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Number of posts to show per page
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Social Media Settings -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Social Media</h3>
                        </div>

                        <div class="p-6 space-y-6">
                            @foreach($settings['social'] ?? [] as $setting)
                                <div>
                                    <label for="{{ $setting->setting_key }}"
                                           class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fab fa-{{ str_replace('social_', '', $setting->setting_key) }} mr-2"></i>
                                        {{ ucwords(str_replace('social_', '', $setting->setting_key)) }}
                                    </label>
                                    <x-text-input id="{{ $setting->setting_key }}"
                                                  name="{{ $setting->setting_key }}"
                                                  type="url"
                                                  class="mt-1 block w-full"
                                                  :value="$setting->setting_value"
                                                  placeholder="https://..." />
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- SEO Settings -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">SEO Settings</h3>
                        </div>

                        <div class="p-6 space-y-6">
                            @foreach($settings['seo'] ?? [] as $setting)
                                <div>
                                    <label for="{{ $setting->setting_key }}"
                                           class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ ucwords(str_replace('seo_', '', $setting->setting_key)) }}
                                    </label>

                                    @if($setting->setting_key === 'seo_meta_description')
                                        <textarea id="{{ $setting->setting_key }}"
                                                  name="{{ $setting->setting_key }}"
                                                  rows="3"
                                                  class="mt-1 block w-full border-gray-300 dark:border-gray-700
                                                         dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500
                                                         dark:focus:border-indigo-600 focus:ring-indigo-500
                                                         dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ $setting->setting_value }}</textarea>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Recommended: 150-160 characters
                                        </p>
                                    @elseif($setting->setting_key === 'seo_meta_keywords')
                                        <textarea id="{{ $setting->setting_key }}"
                                                  name="{{ $setting->setting_key }}"
                                                  rows="2"
                                                  class="mt-1 block w-full border-gray-300 dark:border-gray-700
                                                         dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500
                                                         dark:focus:border-indigo-600 focus:ring-indigo-500
                                                         dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ $setting->setting_value }}</textarea>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Separate keywords with commas
                                        </p>
                                    @else
                                        <x-text-input id="{{ $setting->setting_key }}"
                                                      name="{{ $setting->setting_key }}"
                                                      type="text"
                                                      class="mt-1 block w-full"
                                                      :value="$setting->setting_value" />
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Email Settings -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Email Settings</h3>
                        </div>

                        <div class="p-6 space-y-6">
                            @foreach($settings['email'] ?? [] as $setting)
                                <div>
                                    <label for="{{ $setting->setting_key }}"
                                           class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ ucwords(str_replace(['email_', 'smtp_'], '', $setting->setting_key)) }}
                                    </label>

                                    @if($setting->setting_type === 'number')
                                        <x-text-input id="{{ $setting->setting_key }}"
                                                      name="{{ $setting->setting_key }}"
                                                      type="number"
                                                      class="mt-1 block w-full"
                                                      :value="$setting->setting_value" />
                                    @else
                                        <x-text-input id="{{ $setting->setting_key }}"
                                                      name="{{ $setting->setting_key }}"
                                                      type="{{ $setting->setting_key === 'email_smtp_password' ? 'password' : 'text' }}"
                                                      class="mt-1 block w-full"
                                                      :value="$setting->setting_key === 'email_smtp_password' ? '' : $setting->setting_value"
                                                      autocomplete="new-password" />

                                        @if($setting->setting_key === 'email_smtp_password')
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                Leave blank to keep current password
                                            </p>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-end gap-4">
                                <x-primary-button>
                                    <i class="fas fa-save mr-2"></i> Save All Settings
                                </x-primary-button>

                                <a href="{{ route('admin.dashboard') }}"
                                   class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
