<!-- resources/views/livewire/like-button.blade.php -->
<div>
    <button wire:click="toggleLike"
            class="flex items-center space-x-2 text-gray-600 dark:text-gray-300 hover:text-red-500 dark:hover:text-red-400 transition-colors">
        @if($isLiked)
            <i class="fas fa-heart text-red-500"></i>
            <span class="font-medium text-red-500">{{ $likesCount }}</span>
        @else
            <i class="far fa-heart"></i>
            <span class="font-medium">{{ $likesCount }}</span>
        @endif
    </button>
</div>
