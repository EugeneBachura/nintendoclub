<x-app-layout>
    <x-slot name="lang">
        {{ $post->language }}
    </x-slot>
    <x-slot name="title">
        {{ $post->title }}
    </x-slot>
    <x-slot name="seo_description">
        {{ $seo_description }}
    </x-slot>
    <x-slot name="seo_keywords">
        {{ $seo_keywords }}
    </x-slot>
    <x-slot name="main_img_url">
        {{ asset('storage/posts_images/' . $post->image) }}
    </x-slot>

    <x-slot name="header_img">
        <div class="pb-6 px-0 hidden sm:block md:px-6">
            <x-breadcrumb :breadcrumbs="$breadcrumbs" />
        </div>
        <div class="flex z-0 justify-center items-center w-full min-h-[170px] sm:min-h-[400px] sm:rounded-t-lg relative"
            style="background-image: url('{{ asset('storage/posts_images/' . $post->image) }}'); background-size: cover; background-position: center center;">
            <div class="w-full h-full bg-black absolute top-0 left-0 opacity-30 -z-10 sm:rounded-t-lg"></div>
        </div>
    </x-slot>
    <x-slot name="slim"></x-slot>

    <div class="">
        <div class="flex flex-col sm:flex-row justify-between space-x-4">
            <div class="flex items-center">
                <h1 class="font-semibold text-2xl text-color_text leading-tight z-10 uppercase">
                    {{ $post->title }}
                </h1>
            </div>
            <div class="pl-3 flex justify-end space-x-1">
                <div class="flex space-x-3">
                    @livewire('posts.like-counter', ['postId' => $post->id])
                    @livewire('posts.view-counter', ['postId' => $post->id])
                    @livewire('posts.comment-counter', ['postId' => $post->id])
                </div>
            </div>
        </div>
        <div class="mt-4">
            <div class="space-y-2 text-base">
                {!! $post->content !!}
            </div>
        </div>

        <div class="flex flex-col space-y-8 mt-8">
            @if ($user_level >= 2)
                <div id="comment-form-container" class="mt-2 mx-0 w-full pl-0">
                    <form id="comment_form" action="{{ route('comments.store') }}" method="POST">
                        @csrf
                        <x-textarea id="comment_content" class="block mt-1 -ml-1 w-full" rows="3" name="content"
                            placeholder="{{ __('interfaces.leave_comment') }}"></x-textarea>
                        <input type="hidden" name="parent_id" id="parent_id" value="">
                        <input type="hidden" name="post_id" id="post_id" value="{{ $post->id }}">
                        <div class="flex justify-end items-center">
                            <div id="comment-reply-to" class="hidden text-sm opacity-50 mr-2">
                                {{ __('interfaces.response_comment') }}: <span id="reply-to-name"></span>
                            </div>
                            <button
                                class="inline-flex items-center px-4 py-2 bg-background border border-transparent rounded-md font-semibold text-xs text-success-text uppercase tracking-widest hover:bg-background-hover active:bg-success-900 focus:outline-none focus:border-success-900 focus:ring focus:ring-success-300 disabled:opacity-25 transition"
                                type="submit">
                                {{ __('buttons.send') }}
                            </button>
                        </div>
                    </form>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.querySelectorAll('.reply-button').forEach(button => {
                            button.addEventListener('click', function() {
                                const commentId = this.dataset.commentId;
                                const userName = this.dataset.userName;
                                document.getElementById('parent_id').value = commentId;
                                document.getElementById('reply-to-name').textContent = userName;
                                document.getElementById('comment-reply-to').classList.remove('hidden');
                                document.getElementById('comment-form-container').classList.add('sm:pl-12');

                                const formContainer = document.getElementById('comment-form-container');
                                this.parentElement.parentElement.parentElement.appendChild(formContainer);

                                document.getElementById('comment_content').focus();
                            });
                        });
                    });
                </script>
            @endif

            @foreach ($post->comments as $comment)
                @php
                    $nickname_color = $comment->design?->nickname_color ?? '#ffffff';
                @endphp
                @include('components.comments', [
                    'comment' => $comment,
                    'depth' => 0,
                    'nickname_color' => $nickname_color,
                ])
            @endforeach
        </div>

        <style>
            .content h2 {
                font-size: 1.5rem;
            }

            .content h3 {
                font-size: 1.25rem;
            }

            .content h4 {
                font-size: 1.125rem;
            }

            ol li {
                list-style-type: decimal;
                margin-left: 20px;
            }

            ul li {
                list-style-type: disc;
                margin-left: 20px;
            }

            blockquote {
                overflow: hidden;
                padding-right: 1.5em;
                padding-left: 1.5em;
                margin-left: 0;
                margin-right: 0;
                font-style: italic;
                border-left: 5px solid #ccc;
            }

            figure.image {
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
            }

            figure.image.image-style-side {
                float: right;
                margin: 0 0 1em 1em;
            }

            /* mobile */
            @media (max-width: 640px) {
                figure.image.image-style-side {
                    margin: 0;
                    padding: 0;
                    display: flex;
                    justify-content: center;
                    float: none;
                }
            }
        </style>

    </div>
</x-app-layout>
