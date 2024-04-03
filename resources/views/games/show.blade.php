<x-app-layout>
    {{-- <x-slot name="slim"></x-slot> --}}
    <x-slot name="header">
    </x-slot>
    <x-slot name="title">
        {{$game->name}}
    </x-slot>
    <x-slot name="seo_description">
        {{$game->seo_description}}
    </x-slot>
    <x-slot name="seo_keywords">
        {{$game->seo_keywords}}
    </x-slot>
    <x-slot name="main_img_url">
        {{ asset('storage/' . $game->cover_image_url) }}
    </x-slot>

    <div class="text-color_text space-y-5 relative pt-4">
        <div x-data="{ open: false }" class="flex justify-end -right-2 -top-2 absolute">
            <div @click="open = !open" class="cursor-pointer ring-1 ring-content_text ring-opacity-25 flex items-center justify-center p-1 bg-content-hover rounded-lg hover:bg-content-hover">
                <div class="h-6 w-6 mr-1"><x-icon name="language"></x-icon></div>
                <div class="font-bold text-sm mr-1">{{__('interfaces.language')}}</div>
            </div>
        
            <div x-show="open" @click.away="open = false" class="absolute z-50 mt-6 w-24 rounded-md shadow-lg origin-top-right right-0">
                <div class="rounded-md ring-1 ring-content_text ring-opacity-25 py-1 bg-content">
                    @foreach(['en', 'ru', 'pl'] as $lang)
                            @if ($lang == 'en')
                                <a href="{{route('game.show', ['alias'=>$game->alias])}}" class="block w-full px-4 py-1 text-left text-sm leading-5 text-content_text hover:bg-content-hover focus:outline-none focus:bg-content-hover transition duration-150 ease-in-out">
                                    English
                                </a>
                            @else
                                <a href="{{route('game.show.locale', ['alias'=>$game->alias, 'locale'=>$lang])}}" class="block w-full px-4 py-1 text-left text-sm leading-5 text-content_text hover:bg-content-hover focus:outline-none focus:bg-content-hover transition duration-150 ease-in-out">
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
        <div class="sm:rounded-t-lg">
            <div class="flex flex-col md:flex-row space-y-6 md:space-y-0">
                @if ($game->video)
                    <div>
                        <iframe class="sm:rounded-lg w-full min-h-[250px] h-full xl:w-[650px] md:w-[450px]" src="{{$game->video}}&amp;" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </div>
                @else
                    <div class="flex z-0 justify-center items-center xl:w-[800px] xl:h-[400px] sm:rounded-tl-lg relative" style="background-image: url('{{ asset('storage/' . $game->cover_image_url) }}'); background-size: cover; background-position: center center;">
                    </div>
                @endif
                <div class="flex flex-col justify-between space-y-2 px-0 md:px-6 flex-1">
                    <h1 class="text-2xl font-semibold mb-2 text-content_text-hover">
                        {{$game->name}}
                    </h1>
                    <div class="flex flex-row space-y-2 rounded-lg p-4 bg-background">
                        {{-- border-2 border-grey-text border-opacity-25 --}}
                        <div class="flex flex-1 flex-col space-y-2">
                            <div class="">
                                <h3 class="text-xl font-semibold">{{__('interfaces.platform')}}:</h3> {{$game->platform}}
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold">{{__('interfaces.developer')}}:</h3> {{$game->developer}}
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold">{{__('interfaces.publisher')}}:</h3> {{$game->publisher}}
                            </div>
                            <div>
                                @if (stristr($game->platform, ','))
                                    <h3 class="text-xl font-semibold">{{__('interfaces.first_release')}}:</h3> {{date("d-m-Y", strtotime($game->release_date))}}
                                @else
                                    <h3 class="text-xl font-semibold">{{__('interfaces.release')}}:</h3> {{date("d-m-Y", strtotime($game->release_date))}}
                                @endif
                            </div>
                        </div>
                        <div class="flex items-end">
                            @php
                                if (!$game->average_score) $game->average_score = '4.9';
                                $score_color = 'bg-accent';
                                if ($game->average_score > 2){
                                    $score_color = 'bg-warn-hover';
                                    if ($game->average_score > 4){
                                        $score_color = 'bg-success';
                                    }
                                } 
                            @endphp
                            <h3 class="text-2xl font-semibold rounded-full {{$score_color}} text-successfully-text h-16 w-16 flex items-center justify-center">{{ number_format($game->average_score, 1) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col md:flex-row space-y-6 md:space-y-0">
            <div class="w-full h-full xl:w-[650px] md:w-[450px]">
                {{$game->description}}
            </div>
            <div class="flex flex-col space-y-2 px-0 md:px-6 flex-1">
            @role('user')
                @if ($user_level >= 4)
                    @if (isset($review))
                        <h3 class="text-xl font-semibold mb-2">{{__('interfaces.your_review')}}</h3>
                        <form id="review_form" action="{{ route('reviews.update', ['gameId' => $game->id, 'id' => $review->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            {{-- @if ($review->status != 'published') --}}
                                <div class="text-xs opacity-50">{{__('interfaces.status')}}: {{__('interfaces.'.$review->status)}}
                                    <p>@if ($review->status == 'closed')
                                        {{$review->status_text}}
                                    @endif
                                    </p>
                                </div>
                            {{-- @endif --}}
                            <x-textarea id="content" class="block mt-1 -ml-1 w-full" rows="3" name="content" value="{{old('content', $review->content)}}"></x-textarea>
                            <div class="flex justify-between items-center mt-2">
                                <div class="flex space-x-2" id="star-rating" x-data="starRating()" x-init="setRating({{ $review->rating ?? 0 }})" @mouseleave="leaveRating()">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button
                                            type="button"
                                            @mouseover="hoverRating({{ $i }})"
                                            @click="setRating({{ $i }})"
                                            :class="{'h-6 w-6 star-button transition transform hover:scale-125 duration-300 ease-in-out': true, 'filled': rating >= {{ $i }}}"
                                            id="star-{{ $i }}">
                                            <template x-if="rating >= {{ $i }}">
                                                <x-icon name="star"></x-icon>
                                            </template>
                                            <template x-if="rating < {{ $i }}">
                                                <x-icon name="star-black"></x-icon>
                                            </template>
                                        </button>
                                    @endfor
                                    <input type="hidden" name="rating" x-model="rating">
                                </div>

                                <button class="inline-flex items-center px-4 py-2 bg-background border border-transparent rounded-md font-semibold text-xs text-success-text uppercase tracking-widest hover:bg-background-hover active:bg-success-900 focus:outline-none focus:border-success-900 focus:ring focus:ring-success-300 disabled:opacity-25 transition ml-4" type="submit" form="review_form">{{__('buttons.update')}}</button>
                            </div>
                        </form>
                    @else
                        <h3 class="text-xl font-semibold">{{__('interfaces.write_review')}}</h3>
                        <form id="review_form" action="{{ route('reviews.store', ['gameId' => $game->id]) }}" method="POST">
                        @csrf
                            @method('POST')
                            <x-textarea id="content" class="block mt-1 -ml-1 w-full" rows="5" name="content" value="{{old('content')}}"></x-textarea>
                            <div class="flex justify-between items-center mt-2">
                                <div class="flex space-x-2" id="star-rating" x-data="starRating()" x-init="setRating({{ $review->rating ?? 0 }})" @mouseleave="leaveRating()">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button
                                            type="button"
                                            @mouseover="hoverRating({{ $i }})"
                                            @click="setRating({{ $i }})"
                                            :class="{'h-6 w-6 star-button transition transform hover:scale-125 duration-300 ease-in-out': true, 'filled': rating >= {{ $i }}}"
                                            id="star-{{ $i }}">
                                            <template x-if="rating >= {{ $i }}">
                                                <x-icon name="star"></x-icon>
                                            </template>
                                            <template x-if="rating < {{ $i }}">
                                                <x-icon name="star-black"></x-icon>
                                            </template>
                                        </button>
                                    @endfor
                                    <input type="hidden" name="rating" x-model="rating">
                                </div>
                                
                                <button class="inline-flex items-center px-4 py-2 bg-background border border-transparent rounded-md font-semibold text-xs text-success-text uppercase tracking-widest hover:bg-background-hover active:bg-success-900 focus:outline-none focus:border-success-900 focus:ring focus:ring-success-300 disabled:opacity-25 transition ml-4" type="submit" form="review_form">{{__('buttons.send')}}</button>
                                {{-- <x-button-save :text={{__('buttons.send')}} class="ml-4" form="review_form"/> --}}
                            </div>
                        </form>
                    @endif

                    <script>
                        function starRating() {
                            return {
                                rating: 0,
                                originalRating: 0,
                                setRating(newRating) {
                                    this.rating = newRating;
                                    this.originalRating = newRating;
                                },
                                hoverRating(newRating) {
                                    this.rating = newRating; 
                                },
                                leaveRating() {
                                    this.rating = this.$el.querySelector('input[name="rating"]').value;
                                    this.rating = this.originalRating;
                                }
                            };
                        }
                    </script>
                @endif
                
            @endrole
            </div>
        </div>
        <div class="flex flex-col space-y-8 mt-8">
            @foreach ($reviews as $review_item)
                <div class="flex flex-col justify-between space-y-1">
                    <div class="flex items-center content-center space-x-2">
                        @if (app()->getLocale() == 'en')
                            <a href="{{route('profile.show', ['id' => $review_item->user_id])}}">
                        @else
                            <a href="{{route('profile.show.locale', ['id' => $review_item->user_id, 'locale'=>$lang])}}">
                        @endif
                            <div class="w-8 h-8 rounded-full overflow-hidden sm:flex sm:items-center sm:mr-3 mr-2">
                                <img src="{{$review_item->avatar()}}" alt="User Avatar" class="object-cover w-full h-full">
                            </div>
                        </a>
                        <div class="py-2 flex flex-col space-y-1">
                            @php
                                $nickname_color = '#ffffff';
                                if ($review_item->design()) {
                                    $nickname_color = $review_item->design()->nickname_color;
                                };
                                $lang = app()->getLocale();
                            @endphp
                            <div class="font-semibold text-base text-color_text leading-tight" style="color:{{$nickname_color}}">
                                @if (app()->getLocale() == 'en')
                                    <a href="{{route('profile.show', ['id' => $review_item->user_id])}}">{{$review_item->nickname()}}</a>
                                @else
                                    <a href="{{route('profile.show.locale', ['id' => $review_item->user_id, 'locale'=>$lang])}}">{{$review_item->nickname()}}</a>
                                @endif
                                
                            </div>
                            {{-- <div class="flex space-x-1">
                                @for ($i = 0; $i < $review_item->rating; $i++)
                                    <div class="w-4 h-4">
                                        <x-icon name="star"></x-icon>
                                    </div>
                                @endfor
                                @for ($i = $review_item->rating; $i < 5; $i++)
                                    <div class="w-4 h-4">
                                        <x-icon name="star-black"></x-icon>
                                    </div>
                                @endfor
                            </div> --}}
                        </div>
                    </div>
                    <div class="flex items-center content-center space-x-2 w-full {{--xl:w-[650px] md:w-[450px]--}} md:pr-6">
                        <div class="rounded-lg ml-0 sm:ml-12 p-3 pb-7 bg-background bg-opacity-50 relative w-full">
                            <div class="opacity-50 mb-2">{{$review_item->content}}</div>
                            <div class="flex space-x-1 absolute bottom-3 right-3">
                                @for ($i = 0; $i < $review_item->rating; $i++)
                                    <div class="w-4 h-4">
                                        <x-icon name="star"></x-icon>
                                    </div>
                                @endfor
                                @for ($i = $review_item->rating; $i < 5; $i++)
                                    <div class="w-4 h-4">
                                        <x-icon name="star-black"></x-icon>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
                    {{-- <div class="w-16 h-16 rounded-full overflow-hidden sm:flex sm:items-center sm:mr-6 mr-3">
                        <img src="{{$review_item->avatar()}}" alt="User Avatar" class="object-cover w-full h-full">
                    </div> --}}
            @endforeach
        </div>
    </div>
</x-app-layout>