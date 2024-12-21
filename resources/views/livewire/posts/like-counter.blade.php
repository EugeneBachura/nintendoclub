<div wire:poll.5s="updateLikes" class="like-container flex justify-end items-center space-x-2" wire:click="toggleLike">
    <x-icon-with-text :icon="'like'" :fill="$isLiked ? '#ff3b3c' : '#252525'" class="cursor-pointer">
        <span>{{ $likesCount }}</span>
    </x-icon-with-text>
</div>
