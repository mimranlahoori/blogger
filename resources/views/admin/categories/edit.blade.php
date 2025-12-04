<!-- resources/views/admin/categories/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Category: ') . $category->name }}
            </h2>
            <a href="{{ route('admin.categories.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to Categories
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Current Image Preview -->
                        @if($category->image)
                            <div>
                                <x-input-label :value="__('Current Image')" />
                                <div class="mt-2">
                                    <img src="{{ asset('storage/categories/' . $category->image) }}"
                                         alt="{{ $category->name }}"
                                         class="h-32 w-32 object-cover rounded-lg">
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Current image will be replaced if you upload a new one
                                    </p>
                                </div>
                            </div>
                        @endif

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Category Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                          :value="old('name', $category->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="3"
                                      class="mt-1 block w-full border-gray-300 dark:border-gray-700
                                             dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500
                                             dark:focus:border-indigo-600 focus:ring-indigo-500
                                             dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $category->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- Parent Category -->
                        <div>
                            <x-input-label for="parent_id" :value="__('Parent Category')" />
                            <select id="parent_id" name="parent_id"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700
                                           dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500
                                           dark:focus:border-indigo-600 focus:ring-indigo-500
                                           dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">None (Main Category)</option>
                                @foreach($categories as $parentCategory)
                                    @if($parentCategory->id !== $category->id)
                                        <option value="{{ $parentCategory->id }}"
                                                {{ old('parent_id', $category->parent_id) == $parentCategory->id ? 'selected' : '' }}>
                                            {{ $parentCategory->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('parent_id')" />
                        </div>

                        <!-- New Image -->
                        <div>
                            <x-input-label for="image" :value="__('New Category Image')" />
                            <input id="image" name="image" type="file"
                                   class="mt-1 block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-full file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-blue-50 file:text-blue-700
                                          hover:file:bg-blue-100
                                          dark:file:bg-blue-900 dark:file:text-blue-200
                                          dark:hover:file:bg-blue-800"
                                   accept="image/*">
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <x-input-label for="sort_order" :value="__('Sort Order')" />
                            <x-text-input id="sort_order" name="sort_order" type="number"
                                          class="mt-1 block w-full" :value="old('sort_order', $category->sort_order)" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Lower numbers appear first
                            </p>
                            <x-input-error class="mt-2" :messages="$errors->get('sort_order')" />
                        </div>

                        <!-- Status -->
                        <div class="flex items-center">
                            <input id="is_active" name="is_active" type="checkbox"
                                   class="rounded border-gray-300 text-blue-600 shadow-sm
                                          focus:border-blue-300 focus:ring focus:ring-blue-200
                                          focus:ring-opacity-50"
                                   {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <x-input-label for="is_active" :value="__('Active Category')" class="ml-2" />
                            <x-input-error class="mt-2" :messages="$errors->get('is_active')" />
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center gap-4">
                            <x-primary-button>
                                <i class="fas fa-save mr-2"></i> Update Category
                            </x-primary-button>
                            <a href="{{ route('admin.categories.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
