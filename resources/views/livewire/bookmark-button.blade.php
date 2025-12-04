<!-- resources/views/livewire/bookmark-button.blade.php -->
<div>
    <button wire:click="toggleBookmark"
            @class([
                'flex items-center space-x-2 transition-colors',
                'text-yellow-500 hover:text-yellow-600' => $isBookmarked,
                'text-gray-600 dark:text-gray-300 hover:text-yellow-500' => !$isBookmarked
            ])>
        @if($isBookmarked)
            <i class="fas fa-bookmark"></i>
            <span class="font-medium">Saved</span>
        @else
            <i class="far fa-bookmark"></i>
            <span class="font-medium">Save</span>
        @endif
    </button>
</div>
