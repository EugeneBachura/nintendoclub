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
                    <x-breadcrumb :breadcrumbs="[
                        ['title' => __('titles.all_posts'), 'url' => '']
                    ]" />
                </div>
            </div>
            @if (auth()->user()) 
                @if (auth()->user()->profile->level >= 3)
                    <div>
                        <x-button-link href="{{ route('news.create') }}">
                            {{__('buttons.add_post')}}
                        </x-button-link>
                    </div>
                @endif
            @endif
        </div>
    </x-slot>

    <div class="">
        <div x-data="{ open: false }" class="flex justify-end mb-2 relative w-full">
            <div @click="open = !open" class="cursor-pointer ring-1 ring-content_text ring-opacity-25 flex items-center justify-center p-1 bg-content rounded-lg hover:bg-content-hover">
                <div class="h-6 w-6 mr-1"><x-icon name="language"></x-icon></div>
                <div class="font-bold text-sm mr-1">{{__('interfaces.language')}}</div>
            </div>
        
            <div x-show="open" @click.away="open = false" class="absolute z-50 mt-6 w-24 rounded-md shadow-lg origin-top-right right-0">
                <div class="rounded-md ring-1 ring-content_text ring-opacity-25 py-1 bg-content">
                    @foreach(['en', 'ru', 'pl'] as $lang)
                        @if ($lang == 'en')
                            <a href="{{route('post.showAll')}}" class="block w-full px-4 py-1 text-left text-sm leading-5 text-content_text hover:bg-content-hover focus:outline-none focus:bg-content-hover transition duration-150 ease-in-out">
                                English
                            </a>
                        @else
                            <a href="{{route('post.showAll.locale', ['locale' => $lang])}}" class="block w-full px-4 py-1 text-left text-sm leading-5 text-content_text hover:bg-content-hover focus:outline-none focus:bg-content-hover transition duration-150 ease-in-out">
                                @if ($lang == 'ru')
                                    Русский
                                @endif
                                @if ($lang == 'pl')
                                    Polski
                                @endif
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto">
            <div class="overflow-hidden">
                    <div class="flex flex-wrap -m-4">
                        @foreach ($posts as $post)
                            <div class="p-4 md:w-1/3">
                                <div class="h-full border-opacity-50 shadow border border-content_text rounded-lg overflow-hidden">
                                    @if($post->image)
                                        <img class="lg:h-48 md:h-36 w-full object-cover object-center" src="{{ asset('storage/posts_images/' . $post->image) }}" alt="post image">
                                    @endif
                                    <div class="px-6 pt-5 pb-3">
                                        <h2 class="tracking-widest text-xs title-font font-medium opacity-50 mb-1">{{ $post->categoryName }}</h2>
                                        <h1 class="text-lg font-bold mb-3">{{ $post->title }}</h1>
                                        {{-- <p class="leading-relaxed mb-3">{!! Str::limit($post->content, 200) !!}</p> --}}
                                        <div class="flex items-center justify-between flex-wrap">
                                            <div class="flex space-x-3">
                                                @php
                                                    $isLiked = $post->likes->contains('user_id', Auth::id());
                                                @endphp
                                                <x-icon-with-text icon="like" fill="{{$isLiked ? '#ff3b3c' : '#252525'}}" tooltip="{{__('interfaces.likes')}}">{{$post->likes->count()}}</x-icon-with-text>
                                                <x-icon-with-text icon="eye" fill="#252525" tooltip="{{__('interfaces.views')}}">{{$post->views_count}}</x-icon-with-text>
                                                <x-icon-with-text icon="comments" fill="#252525" tooltip="{{__('interfaces.comments')}}">{{$post->comments_count}}</x-icon-with-text>
                                            </div>
                                            @php
                                                if (app()->getLocale() == "en") {
                                                    $href = route('post.show', ['alias' => $post->alias]);
                                                } else {
                                                    $href = route('post.show.locale', ['alias' => $post->alias, 'locale' => app()->getLocale()]);
                                                }
                                            @endphp
                                            <a href="{{ $href }}" class="text-accent transition hover:scale-105 hover:text-content_text hover:underline inline-flex items-center md:mb-2 lg:mb-0">
                                            {{__('interfaces.learn_more')}}
                                                <svg class="w-4 h-4 ml-2" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
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
