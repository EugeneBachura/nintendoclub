<x-app-layout>
    <x-slot name="title">
        {{ $game->localizedName }}
    </x-slot>
    <x-slot name="seo_description">
        {{ $game->localizedSeoDescription }}
    </x-slot>
    <x-slot name="seo_keywords">
        {{ $game->localizedSeoKeywords }}
    </x-slot>
    <x-slot name="main_img_url">
        {{ asset('storage/' . $game->cover_image_url) }}
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center mb-2">
            <h2 class="font-semibold text-xl text-content_text leading-tight">
                {{ $game->localizedName }}
            </h2>
        </div>
        <div>
            <x-breadcrumb :breadcrumbs="$breadcrumbs" />
        </div>
    </x-slot>

    <div class="text-color_text space-y-5 relative pt-4">

        <div class="sm:rounded-t-lg">
            <div class="flex flex-col md:flex-row space-y-6 md:space-y-0">
                @if ($game->video)
                    <div>
                        <iframe class="sm:rounded-lg w-full min-h-[250px] h-full xl:w-[650px] md:w-[450px]"
                            src="{{ $game->video }}&amp;" title="YouTube video player" frameborder="0"
                            allowfullscreen></iframe>
                    </div>
                @else
                    <div class="flex z-0 justify-center items-center xl:w-[800px] xl:h-[400px] sm:rounded-tl-lg relative"
                        style="background-image: url('{{ asset('storage/' . $game->cover_image_url) }}'); background-size: cover; background-position: center center;">
                    </div>
                @endif
                <div class="flex flex-col justify-between space-y-2 px-0 md:px-6 flex-1">
                    <h1 class="text-2xl font-semibold mb-2 text-content_text-hover">
                        {{ $game->localizedName }}
                    </h1>
                    <div class="flex flex-row space-y-2 rounded-lg p-4 bg-background">
                        <div class="flex flex-1 flex-col space-y-2">
                            <div>
                                <h3 class="text-xl font-semibold">{{ __('interfaces.platform') }}:</h3>
                                {{ $game->platform }}
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold">{{ __('interfaces.developer') }}:</h3>
                                {{ $game->developer }}
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold">{{ __('interfaces.publisher') }}:</h3>
                                {{ $game->publisher }}
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold">
                                    {{ $isMultiplePlatforms ? __('interfaces.first_release') : __('interfaces.release') }}:
                                </h3> {{ $releaseDate }}
                            </div>
                        </div>
                        <div class="flex items-end">
                            <h3
                                class="text-2xl font-semibold rounded-full {{ $game->score_color }} text-successfully-text h-16 w-16 flex items-center justify-center">
                                {{ number_format($game->average_score, 1) }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row space-y-6 md:space-y-0">
            <div class="w-full h-full xl:w-[650px] md:w-[450px]">
                {!! $game->localizedDescription !!}
            </div>
            <div class="flex flex-col space-y-2 px-0 md:px-6 flex-1">
                @if ($user_level >= 4)
                    @if ($review)
                        <!-- Форма редактирования отзыва -->
                        <h3 class="text-xl font-semibold mb-2">{{ __('interfaces.your_review') }}</h3>
                        <form id="review_form"
                            action="{{ route('reviews.update', ['gameId' => $game->id, 'id' => $review->id]) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="text-xs opacity-50">{{ __('interfaces.status') }}:
                                {{ __('interfaces.' . $review->status) }}
                                @if ($review->status == 'closed')
                                    <p>{{ $review->status_text }}</p>
                                @endif
                            </div>
                            <x-textarea id="content" class="block mt-1 -ml-1 w-full" rows="3"
                                name="content">{{ old('content', $review->content) }}</x-textarea>
                            <div class="flex justify-between items-center mt-2">
                                <div class="flex space-x-2" x-data="starRating()" x-init="setRating({{ $review->rating ?? 0 }})"
                                    @mouseleave="leaveRating()">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button type="button" @mouseover="hoverRating({{ $i }})"
                                            @click="setRating({{ $i }})"
                                            :class="{
                                                'h-6 w-6 transition transform hover:scale-125 duration-300 ease-in-out': true,
                                                'filled': rating >=
                                                    {{ $i }}
                                            }"
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

                                <button
                                    class="inline-flex items-center px-4 py-2 bg-background border border-transparent rounded-md font-semibold text-xs text-success-text uppercase tracking-widest hover:bg-background-hover focus:outline-none focus:border-success-900 focus:ring focus:ring-success-300 disabled:opacity-25 transition ml-4"
                                    type="submit">{{ __('buttons.update') }}</button>
                            </div>
                        </form>
                    @else
                        <!-- Форма добавления нового отзыва -->
                        <h3 class="text-xl font-semibold">{{ __('interfaces.write_review') }}</h3>
                        <form id="review_form" action="{{ route('reviews.store', ['gameId' => $game->id]) }}"
                            method="POST">
                            @csrf
                            <x-textarea id="content" class="block mt-1 -ml-1 w-full" rows="5"
                                name="content">{{ old('content') }}</x-textarea>
                            <div class="flex justify-between items-center mt-2">
                                <div class="flex space-x-2" x-data="starRating()" x-init="setRating(0)"
                                    @mouseleave="leaveRating()">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button type="button" @mouseover="hoverRating({{ $i }})"
                                            @click="setRating({{ $i }})"
                                            :class="{
                                                'h-6 w-6 transition transform hover:scale-125 duration-300 ease-in-out': true,
                                                'filled': rating >=
                                                    {{ $i }}
                                            }"
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

                                <button
                                    class="inline-flex items-center px-4 py-2 bg-background border border-transparent rounded-md font-semibold text-xs text-success-text uppercase tracking-widest hover:bg-background-hover focus:outline-none focus:border-success-900 focus:ring focus:ring-success-300 disabled:opacity-25 transition ml-4"
                                    type="submit">{{ __('buttons.send') }}</button>
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
                                    this.rating = this.originalRating;
                                }
                            };
                        }
                    </script>
                @endif
            </div>
        </div>

        <div class="flex flex-col space-y-8 mt-8">
            @foreach ($reviews as $review_item)
                <div class="flex flex-col justify-between space-y-1">
                    <div class="flex items-center space-x-2">
                        <a
                            href="{{ route('profile.show', ['id' => $review_item->user_id, 'lang' => app()->getLocale()]) }}">
                            <div class="w-8 h-8 rounded-full overflow-hidden sm:flex sm:items-center sm:mr-3 mr-2">
                                <img src="{{ $review_item->user->avatar() }}" alt="User Avatar"
                                    class="object-cover w-full h-full">
                            </div>
                        </a>
                        <div class="py-2 flex flex-col space-y-1">
                            @php
                                $nickname_color = $review_item->user->design?->nickname_color ?? '#ffffff';
                            @endphp
                            <div class="font-semibold text-base text-color_text leading-tight"
                                style="color: {{ $nickname_color }}">
                                <a
                                    href="{{ route('profile.show', ['id' => $review_item->user_id, 'lang' => app()->getLocale()]) }}">{{ $review_item->user->nickname() }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center content-center space-x-2 w-full md:pr-6">
                        <div class="rounded-lg ml-0 sm:ml-12 p-3 pb-7 bg-background bg-opacity-50 relative w-full">
                            <div class="opacity-50 mb-2">{{ $review_item->content }}</div>
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
            @endforeach
        </div>
    </div>
</x-app-layout>
