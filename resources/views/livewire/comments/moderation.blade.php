<div wire:poll.10s="loadComments">

    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-color_text leading-tight">
                {{ __('titles.comment_moderation') }}
            </h2>
        </div>
    </x-slot>

    <div class="flex justify-between mb-4">
        <div>
            <select wire:model.live="filterStatus" class="border border-opacity-50 rounded p-2 text-sm text-black">
                <option value="all">{{ __('texts.all_comments') }}</option>
                <option value="pending">{{ __('texts.pending_comments') }}</option>
                <option value="approved">{{ __('texts.approved_comments') }}</option>
                <option value="rejected">{{ __('texts.rejected_comments') }}</option>
                <option value="spam">{{ __('texts.spam_comments') }}</option>
            </select>
        </div>
    </div>

    <div class="max-w-7xl mx-auto">
        <div class="overflow-hidden shadow-sm sm:rounded-lg">
            <div class="">
                @if (count($comments) === 0)
                    <p class="text-gray-500">{{ __('texts.no_comments_found') }}</p>
                @else
                    <div class="space-y-4">
                        @foreach ($comments as $comment)
                            <div class="border rounded p-4 flex justify-between items-center">
                                <div>
                                    <p class="text-white mb-2">{{ $comment['content'] }}</p>
                                    <small class="text-gray-500">
                                        {{ __('texts.by_user') }}: <span
                                            class="font-semibold">{{ $comment['user']['name'] }}</span> |
                                        {{ __('texts.on_post') }}: <span
                                            class="font-semibold">{{ $comment['post']['title'] }}</span> |
                                        {{ __('texts.by_moderator') }}: <span
                                            class="font-semibold">{{ $comment?->moderator?->nickname() }}</span> |
                                        {{ __('texts.posted_at') }}:
                                        {{ \Carbon\Carbon::parse($comment['created_at'])->toDateTimeString() }}
                                    </small>
                                </div>
                                <div class="flex space-x-2">
                                    <button wire:click="setStatus({{ $comment['id'] }}, 'approved')"
                                        class="px-3 py-1 rounded transition bg-white hover:bg-green-600
    {{ $comment['status'] == 'pending' || $comment['status'] == 'approved' ? '' : 'opacity-50' }}">
                                        ✔️
                                    </button>

                                    <button wire:click="setStatus({{ $comment['id'] }}, 'rejected')"
                                        class="px-3 py-1 rounded transition bg-white hover:bg-red-600
    {{ $comment['status'] == 'pending' || $comment['status'] == 'rejected' ? '' : 'opacity-50' }}">
                                        ❌
                                    </button>

                                    <button wire:click="setStatus({{ $comment['id'] }}, 'spam')"
                                        class="px-3 py-1 rounded transition bg-white hover:bg-yellow-600
    {{ $comment['status'] == 'pending' || $comment['status'] == 'spam' ? '' : 'opacity-50' }}">
                                        ⚠️
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
