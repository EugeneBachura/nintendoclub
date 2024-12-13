<div class="flex flex-col mt-4" style="margin-left: {{ $depth * 20 }}px;">
    <div class="mb-2">
        <div class="flex items-center content-center space-x-2">
            <div class="w-9 h-9 rounded-full overflow-hidden sm:flex sm:items-center sm:mr-3 mr-2">
                <img src="{{ $comment->user->avatar }}" alt="User Avatar" class="object-cover w-full h-full">
            </div>
            <div class="py-2 flex flex-col space-y-1">
                <div class="font-semibold text-base text-color_text leading-tight" style="color:{{ $nickname_color }}">
                    <a href="{{ route('profile.show', ['id' => $comment->user_id]) }}">{{ $comment->user->name }}</a>
                    <div class="text-xs font-normal text-color_text opacity-50 mt-1">
                        {{ $comment->created_at->diffForHumans() }}</div>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap justify-end items-center content-center w-full">
            <div class="rounded-lg ml-0 sm:ml-12 p-3 pb-0 bg-background bg-opacity-50 relative w-full">
                <div class="">{{ $comment->content }}</div>
                @php
                    $isLikedComment = $comment->likes->contains('user_id', Auth::id());
                @endphp
                <div class="flex items-center justify-end space-x-2 opacity-75">
                    <div class="h-5 w-5 reply-button cursor-pointer" data-comment-id="{{ $comment->id }}"
                        data-user-name="{{ $comment->user->name }}">
                        <x-icon name="response" fill="#252525" />
                    </div>
                    <div class="like-comment-container flex" data-post-comment-id="{{ $comment->id }}"
                        data-is-liked="{{ $isLikedComment ? 'true' : 'false' }}" data-is-processing="false">
                        <x-icon-with-text icon="like" fill="#ff3b3c" class="like-icon active"
                            style="{{ $isLikedComment ? '' : 'display:none;' }}">
                            <span class="likes-count">{{ $comment->likes->count() }}</span>
                        </x-icon-with-text>
                        <x-icon-with-text icon="like" fill="#252525" class="like-icon inactive"
                            style="{{ !$isLikedComment ? '' : 'display:none;' }}">
                            <span class="likes-count">{{ $comment->likes->count() }}</span>
                        </x-icon-with-text>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($comment->replies as $reply)
        @include('components.comments', ['comment' => $reply, 'depth' => $depth + 1])
    @endforeach
</div>

<script>
    document.querySelectorAll('.like-comment-container').forEach(container => {
        container.addEventListener('click', function() {
            const isProcessing = this.getAttribute('data-is-processing') === 'true';
            if (isProcessing) {
                return;
            }
            this.setAttribute('data-is-processing', 'true');

            const commentId = this.dataset.postCommentId;
            const isLiked = this.dataset.isLiked === 'true';
            const likeIconActive = this.querySelector('.like-icon.active');
            const likeIconInactive = this.querySelector('.like-icon.inactive');
            const likesCountSpan = this.querySelector('.likes-count');

            fetch(`/comments/${commentId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    credentials: 'same-origin',
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    likesCountSpan.textContent = data.likesCount;
                    likeIconActive.style.display = isLiked ? 'none' : '';
                    likeIconInactive.style.display = isLiked ? '' : 'none';
                    this.dataset.isLiked = isLiked ? 'false' : 'true';
                })
                .catch(error => {
                    console.error('Error:', error);
                })
                .finally(() => {
                    this.setAttribute('data-is-processing', 'false');
                });
        });
    });
</script>
