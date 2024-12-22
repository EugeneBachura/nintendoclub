<div wire:poll.5s="updateCommentsCount" class="comment-container flex justify-end items-center space-x-2">
    <x-icon-with-text icon="comments" fill="#252525">
        <span>{{ $commentsCount }}</span>
    </x-icon-with-text>
</div>
