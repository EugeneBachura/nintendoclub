<x-app-layout>
    <x-slot name="title">
        {{$news->getTranslation('title', app()->getLocale())}}
    </x-slot>
    <x-slot name="seo_description">
        {{$seo_description}}
    </x-slot>
    <x-slot name="seo_keywords">
        {{$seo_keywords}}
    </x-slot>
    <x-slot name="main_img_url">
        {{ asset('storage/' . $news->image) }}
    </x-slot>

    <x-slot name="header_img">
        <div class="pb-6 px-0 hidden sm:block md:px-6">
            @php
                $breadcrumb_title = $news->getTranslation('title', app()->getLocale());
                $limit = 70;
                if (app()->getLocale() == 'en') $limit = 70;
                if (app()->getLocale() == 'ru') $limit = 60;
                if (app()->getLocale() == 'pl') $limit = 60;
                $ending = '...';

                if (mb_strlen($breadcrumb_title) > $limit) {
                    $cutOff = mb_strripos(mb_substr($breadcrumb_title, 0, $limit), ' ');
                    $trimmed = mb_substr($breadcrumb_title, 0, $cutOff) . $ending;
                } else {
                    $trimmed = $breadcrumb_title;
                }
            @endphp 
            <x-breadcrumb :breadcrumbs="[
                ['title' => __('titles.all_news'), 'url' => localizedRoute('news.showAll')],
                ['title' => $trimmed, 'url' => '']
            ]" />
        </div>
        <div class="flex z-0 justify-center items-center w-full min-h-[120px] sm:min-h-[400px] sm:rounded-t-lg relative" style="background-image: url('{{ asset('storage/' . $news->image) }}'); background-size: cover; background-position: center center;">
            {{-- <h2 class="font-semibold text-2xl text-color_text leading-tight z-10 uppercase">
                {{$news->getTranslation('title', app()->getLocale())}}
            </h2> --}}
            <div class="w-full h-full bg-black absolute top-0 left-0 opacity-30 -z-10 sm:rounded-t-lg"></div>

            <div x-data="{ open: false }" class="flex justify-end absolute top-0 right-0 mt-2 mr-2 w-full">
                <div @click="open = !open" class="cursor-pointer ring-1 ring-content_text ring-opacity-25 flex items-center justify-center p-1 bg-content rounded-lg hover:bg-content-hover">
                    <div class="h-6 w-6 mr-1"><x-icon name="language"></x-icon></div>
                    <div class="font-bold text-sm mr-1">{{__('interfaces.language')}}</div>
                </div>
            
                <div x-show="open" @click.away="open = false" class="absolute z-50 mt-6 w-24 rounded-md shadow-lg origin-top-right right-0">
                    <div class="rounded-md ring-1 ring-content_text ring-opacity-25 py-1 bg-content">
                        <!-- Loop through languages -->
                        {{-- @foreach(['en', 'ru', 'pl'] as $lang) 
                            @if($title = $news->getTranslation('title', $lang))
                                @php
                                    switch ($lang) {
                                        case 'en':
                                            $langTitle = 'English';
                                            break;
                                        case 'ru':
                                            $langTitle = 'Русский';
                                            break;
                                        case 'pl':
                                            $langTitle = 'Polska';
                                            break;
                                        default:
                                            break;
                                    }
                                @endphp
                                @if ($lang == 'en')
                                    <a href="{{ route('news.show', $news->alias) }}" class="block w-full px-4 py-2 text-left text-base leading-5 text-content_text hover:bg-content-hover focus:outline-none focus:bg-content-hover transition duration-150 ease-in-out">
                                        {{$langTitle}}
                                    </a>
                                @else
                                    <a href="{{ route('localized.news.show', ['alias' => $news->alias, 'locale' => $lang]) }}" class="block w-full px-4 py-2 text-left text-base leading-5 text-content_text hover:bg-content-hover focus:outline-none focus:bg-content-hover transition duration-150 ease-in-out">
                                        {{$langTitle}}
                                    </a>
                                @endif
                            @endif
                        @endforeach --}}
                        @foreach(['en', 'ru', 'pl'] as $lang)
                            @if($title = $news->getTranslation('title', $lang))
                                @if ($lang == 'en')
                                    <a href="{{route('news.show', ['alias' => $news->alias])}}" class="block w-full px-4 py-1 text-left text-sm leading-5 text-content_text hover:bg-content-hover focus:outline-none focus:bg-content-hover transition duration-150 ease-in-out">
                                        {{-- {{$news->getTranslation('title', $lang)}} --}}
                                        English
                                    </a>
                                @else
                                    <a href="{{route('news.show.locale', ['alias' => $news->alias, 'locale' => $lang])}}" class="block w-full px-4 py-1 text-left text-sm leading-5 text-content_text hover:bg-content-hover focus:outline-none focus:bg-content-hover transition duration-150 ease-in-out">
                                        {{-- {{$news->getTranslation('title', $lang)}} --}}
                                        @if ($lang == 'ru')
                                            Русский
                                        @endif
                                        @if ($lang == 'pl')
                                            Polski
                                        @endif
                                    </a>
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            
        </div>
        {{-- <div class="h-0.5 bg-content-border w-full"></div> --}}
    </x-slot>
    <x-slot name="slim"></x-slot>

    @php
        function getPopularityColor($popularity) {
            $maxPopularity = 1000;
            $intensity = $popularity / $maxPopularity;
            $red = 255;
            $green = 255 * (1 - $intensity);
            $blue = 0;
            return "rgb($red, $green, $blue)";
        }
    @endphp

    <div class="">
        <div class="flex justify-between space-x-4">
            <div class="flex items-center">
                <h1 class="font-semibold text-2xl text-color_text leading-tight z-10 uppercase">
                    {{$news->getTranslation('title', app()->getLocale())}}
                </h1>
            </div>
            <div class="pl-3 md:pl-0 flex justify-end space-x-1">
                <div class="flex h-max align-middle items-center space-x-1 rounded-lg">
                    <div class="h-5 w-5 mt-0.5">
                        <x-icon name="burn" fill="{{getPopularityColor($news->popularity+400)}}"></x-icon>
                    </div>
                    {{-- <div>
                        {{$news->popularity}}
                    </div> --}}
                </div>
                <div class="flex h-max align-middle items-center space-x-1.5 rounded-lg">
                    {{-- <div class="h-5 w-5">
                        <x-icon name="eye"></x-icon>
                    </div> --}}
                    <div class="mt-0.5">
                        {{$news->views_count}}
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <div class="space-y-2 indent-4">
                {!!$news->getTranslation('content', app()->getLocale())!!}
                <div class="flex w-full justify-center pt-2">
                    @if ($news->video)
                        @php
                            // Пример преобразования URL из https://www.youtube.com/watch?v=VIDEO_ID в https://www.youtube.com/embed/VIDEO_ID
                            $videoId = null;
                            if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $news->video, $matches)) {
                                $videoId = $matches[1];
                            }
                            $embedUrl = $videoId ? "https://www.youtube.com/embed/{$videoId}?controls=0" : null;
                        @endphp
                        @if ($embedUrl)
                            <iframe class="max-w-full" width="560" height="315" src="{{ $embedUrl }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        @endif
                    @endif
                </div>
            </div>
        </div>
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
    </style>
</x-app-layout>