<!-- resources/views/livewire/follow-button.blade.php -->
<div>
    <button wire:click="toggleFollow"
            @class([
                'px-4 py-2 rounded-full font-medium transition-colors',
                'bg-blue-600 text-white hover:bg-blue-700' => !$isFollowing,
                'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600' => $isFollowing
            ])>
        @if($isFollowing)
            <i class="fas fa-user-check mr-2"></i> Following
        @else
            <i class="fas fa-user-plus mr-2"></i> Follow
        @endif
        <span class="ml-2 text-sm">{{ $followersCount }}</span>
    </button>
</div>
