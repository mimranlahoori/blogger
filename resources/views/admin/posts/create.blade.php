<!-- resources/views/admin/posts/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Create New Post') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Write and publish a new blog post</p>
            </div>
            <a href="{{ route('admin.posts.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-arrow-left mr-2"></i> Back to Posts
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data" id="post-form">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column - Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Title -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <x-input-label for="title" :value="__('Post Title')" class="text-lg font-medium mb-4" />
                            <x-text-input id="title" name="title" type="text" class="block w-full text-lg"
                                          placeholder="Enter post title..."
                                          :value="old('title')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <!-- Content -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <x-input-label for="content" :value="__('Content')" class="text-lg font-medium" />
                            </div>
                            <div class="p-6">
                                <textarea id="content" name="content" rows="20"
                                          class="block w-full border-gray-300 dark:border-gray-700
                                                 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500
                                                 dark:focus:border-indigo-600 focus:ring-indigo-500
                                                 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                          placeholder="Start writing your post content here..."
                                          required>{{ old('content') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('content')" />
                            </div>
                        </div>

                        <!-- Excerpt -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <x-input-label for="excerpt" :value="__('Excerpt')" class="text-lg font-medium mb-4" />
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                                A short summary of your post. This will appear in post listings and search results.
                            </p>
                            <textarea id="excerpt" name="excerpt" rows="4"
                                      class="block w-full border-gray-300 dark:border-gray-700
                                             dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500
                                             dark:focus:border-indigo-600 focus:ring-indigo-500
                                             dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                      placeholder="Enter a brief excerpt...">{{ old('excerpt') }}</textarea>
                            <div class="mt-2 flex justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                                <span>Recommended: 150-160 characters</span>
                                <span id="excerpt-counter">0 characters</span>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('excerpt')" />
                        </div>
                    </div>

                    <!-- Right Column - Sidebar -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Publish Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Publish</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <!-- Status -->
                                <div>
                                    <x-input-label for="status" :value="__('Status')" />
                                    <select id="status" name="status"
                                            class="mt-1 block w-full border-gray-300 dark:border-gray-700
                                                   dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500
                                                   dark:focus:border-indigo-600 focus:ring-indigo-500
                                                   dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                    </select>
                                </div>

                                <!-- Published At -->
                                <div id="publish-date-container" class="{{ old('status', 'draft') == 'draft' ? 'hidden' : '' }}">
                                    <x-input-label for="published_at" :value="__('Publish Date')" />
                                    <x-text-input id="published_at" name="published_at" type="datetime-local"
                                                  class="mt-1 block w-full"
                                                  :value="old('published_at')" />
                                </div>

                                <!-- Featured -->
                                <div class="flex items-center pt-4">
                                    <input type="hidden" name="featured" value="0">
                                    <input id="featured" name="featured" type="checkbox"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm
                                                  focus:border-blue-300 focus:ring focus:ring-blue-200
                                                  focus:ring-opacity-50"
                                                  value="1"
                                           {{ old('featured') ? 'checked' : '' }}>
                                    <x-input-label for="featured" :value="__('Featured Post')" class="ml-2" />
                                </div>

                                <div class="pt-6 border-t border-gray-200 dark:border-gray-700 space-y-3">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center mb-1">
                                            <i class="fas fa-user mr-2"></i>
                                            <span>Author: {{ Auth::user()->name }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-clock mr-2"></i>
                                            <span>Created: {{ now()->format('M d, Y') }}</span>
                                        </div>
                                    </div>

                                    <div class="flex space-x-2">
                                        <x-primary-button type="submit" class="flex-1 justify-center">
                                            <i class="fas fa-save mr-2"></i> Save Post
                                        </x-primary-button>

                                        <button type="button"
                                                onclick="document.getElementById('post-form').reset()"
                                                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Featured Image Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Featured Image</h3>
                            </div>
                            <div class="p-6">
                                <!-- Image Preview -->
                                <div id="image-preview" class="mb-4 hidden">
                                    <img id="preview-image" class="w-full h-48 object-cover rounded-lg mb-2">
                                    <button type="button" onclick="removeImage()"
                                            class="text-red-600 hover:text-red-800 text-sm">
                                        <i class="fas fa-trash mr-1"></i> Remove Image
                                    </button>
                                </div>

                                <!-- Upload Area -->
                                <div id="upload-area" class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg p-8 text-center hover:border-blue-400 transition-colors">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-600 dark:text-gray-400 mb-2">Drop image here or click to upload</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-500 mb-4">Recommended: 1200x630 pixels</p>
                                    <label for="image" class="cursor-pointer">
                                        <span class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                            <i class="fas fa-upload mr-2"></i> Select Image
                                        </span>
                                        <input id="image" name="image" type="file"
                                               class="hidden" accept="image/*" onchange="previewImage(event)">
                                    </label>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('image')" />
                            </div>
                        </div>

                        <!-- Categories Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Categories</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
                                    @foreach($categories as $category)
                                        <div class="flex items-center">
                                            <input id="category-{{ $category->id }}"
                                                   name="categories[]"
                                                   type="checkbox"
                                                   value="{{ $category->id }}"
                                                   class="rounded border-gray-300 text-blue-600 shadow-sm
                                                          focus:border-blue-300 focus:ring focus:ring-blue-200
                                                          focus:ring-opacity-50"
                                                   {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                                            <label for="category-{{ $category->id }}"
                                                   class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $category->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('categories')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Tags Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tags</h3>
                            </div>
                            <div class="p-6">
                                <div>
                                    <select id="tags" name="tags[]" multiple
                                            class="mt-1 block w-full border-gray-300 dark:border-gray-700
                                                   dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500
                                                   dark:focus:border-indigo-600 focus:ring-indigo-500
                                                   dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}"
                                                    {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
                                                {{ $tag->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        Hold Ctrl/Cmd to select multiple tags
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- SEO Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">SEO Settings</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <x-input-label for="meta_title" :value="__('Meta Title')" />
                                    <x-text-input id="meta_title" name="meta_title" type="text"
                                                  class="mt-1 block w-full"
                                                  :value="old('meta_title')"
                                                  placeholder="If empty, post title will be used" />
                                </div>

                                <div>
                                    <x-input-label for="meta_description" :value="__('Meta Description')" />
                                    <textarea id="meta_description" name="meta_description" rows="3"
                                              class="mt-1 block w-full border-gray-300 dark:border-gray-700
                                                     dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500
                                                     dark:focus:border-indigo-600 focus:ring-indigo-500
                                                     dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                              placeholder="Brief description for search engines">{{ old('meta_description') }}</textarea>
                                </div>

                                <div>
                                    <x-input-label for="meta_keywords" :value="__('Meta Keywords')" />
                                    <x-text-input id="meta_keywords" name="meta_keywords" type="text"
                                                  class="mt-1 block w-full"
                                                  :value="old('meta_keywords')"
                                                  placeholder="Separate with commas" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Simple WYSIWYG Editor
        document.addEventListener('DOMContentLoaded', function() {
            // Excerpt character counter
            const excerptTextarea = document.getElementById('excerpt');
            const excerptCounter = document.getElementById('excerpt-counter');

            excerptTextarea.addEventListener('input', function() {
                excerptCounter.textContent = this.value.length + ' characters';
            });

            // Initialize excerpt counter
            excerptCounter.textContent = excerptTextarea.value.length + ' characters';

            // Show/hide publish date based on status
            const statusSelect = document.getElementById('status');
            const publishDateContainer = document.getElementById('publish-date-container');

            statusSelect.addEventListener('change', function() {
                if (this.value === 'published') {
                    publishDateContainer.classList.remove('hidden');

                    // Set default publish date to now if not set
                    if (!document.getElementById('published_at').value) {
                        const now = new Date();
                        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                        document.getElementById('published_at').value = now.toISOString().slice(0, 16);
                    }
                } else {
                    publishDateContainer.classList.add('hidden');
                }
            });

            // Image preview
            window.previewImage = function(event) {
                const input = event.target;
                const preview = document.getElementById('preview-image');
                const previewContainer = document.getElementById('image-preview');
                const uploadArea = document.getElementById('upload-area');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                        uploadArea.classList.add('hidden');
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            };

            window.removeImage = function() {
                const previewContainer = document.getElementById('image-preview');
                const uploadArea = document.getElementById('upload-area');
                const fileInput = document.getElementById('image');

                previewContainer.classList.add('hidden');
                uploadArea.classList.remove('hidden');
                fileInput.value = '';
            };
        });
    </script>
    @endpush
</x-app-layout>
