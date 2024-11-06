<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between">
            <div>
                <div class="flex justify-between items-center mb-2">
                    <h2 class="font-semibold text-xl text-content_text leading-tight">
                        {{ __('titles.all_posts') }}
                    </h2>
                </div>
                <div>
                    <x-breadcrumb :breadcrumbs="$breadcrumbs" />
                </div>
            </div>
            @if (auth()->user() && auth()->user()->profile->level >= 3)
                <div>
                    <x-button-link href="{{ route('post.create') }}">
                        {{ __('buttons.add_post') }}
                    </x-button-link>
                </div>
            @endif
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto">
            <div class="overflow-hidden">
                <div class="flex flex-wrap -m-4">
                    @foreach ($posts as $post)
                        <div class="p-4 md:w-1/3">
                            <div
                                class="h-full border-opacity-50 shadow border border-content_text rounded-lg overflow-hidden">
                                @if ($post->image)
                                    <img class="lg:h-48 md:h-36 w-full object-cover object-center"
                                        src="{{ asset('storage/posts_images/' . $post->image) }}" alt="post image">
                                @endif
                                <div class="px-6 pt-5 pb-3">
                                    <h2 class="tracking-widest text-xs title-font font-medium opacity-50 mb-1">
                                        {{ $post->category->name }}
                                    </h2>
                                    <h1 class="text-lg font-bold mb-3">{{ $post->title }}</h1>
                                    <p class="text-sm">{{ $post->trimmedContent }}</p>
                                    <div class="flex items-center justify-between flex-wrap">
                                        <div class="flex space-x-3">
                                            @php $isLiked = $post->likes->contains('user_id', Auth::id()); @endphp
                                            <x-icon-with-text icon="like"
                                                fill="{{ $isLiked ? '#ff3b3c' : '#252525' }}"
                                                tooltip="{{ __('interfaces.likes') }}">
                                                {{ $post->likes->count() }}
                                            </x-icon-with-text>
                                            <x-icon-with-text icon="eye" fill="#252525"
                                                tooltip="{{ __('interfaces.views') }}">
                                                {{ $post->views_count }}
                                            </x-icon-with-text>
                                            <x-icon-with-text icon="comments" fill="#252525"
                                                tooltip="{{ __('interfaces.comments') }}">
                                                {{ $post->comments_count }}
                                            </x-icon-with-text>
                                        </div>
                                        <a href="{{ localized_url('post.show', ['alias' => $post->alias]) }}"
                                            class="text-accent transition hover:scale-105 hover:text-content_text hover:underline inline-flex items-center md:mb-2 lg:mb-0">
                                            {{ __('interfaces.learn_more') }}
                                            <svg class="w-4 h-4 ml-2" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M5 12h14"></path>
                                                <path d="M12 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
