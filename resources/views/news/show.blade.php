<x-app-layout>
    <x-slot name="title">
        {{ $news->getTranslation('title', App::getLocale()) }}
    </x-slot>
    <x-slot name="seo_description">
        {{ $seo_description }}
    </x-slot>
    <x-slot name="seo_keywords">
        {{ $seo_keywords }}
    </x-slot>
    <x-slot name="main_img_url">
        {{ asset('storage/' . $news->image) }}
    </x-slot>

    <x-slot name="header_img">
        <div class="pb-6 px-0 hidden sm:block md:px-6">
            <x-breadcrumb :breadcrumbs="$breadcrumbs" />
        </div>
        <div class="flex z-0 justify-center items-center w-full min-h-[120px] sm:min-h-[400px] sm:rounded-t-lg relative"
            style="background-image: url('{{ asset('storage/' . $news->image) }}'); background-size: cover; background-position: center center;">
            <div class="w-full h-full bg-black absolute top-0 left-0 opacity-30 -z-10 sm:rounded-t-lg"></div>
        </div>
    </x-slot>
    <x-slot name="slim"></x-slot>

    <div class="">
        <div class="flex flex-col sm:flex-row justify-between space-x-4">
            <div class="flex items-center">
                <h1 class="font-semibold text-2xl text-color_text leading-tight z-10 uppercase">
                    {{ $news->getTranslation('title', App::getLocale()) }}
                </h1>
            </div>
            <div class="pl-3 md:pl-0 flex justify-end space-x-3">
                <div class="flex justify-end space-x-1">
                    <div class="flex h-max align-middle items-center space-x-1 rounded-lg">
                        <div class="h-5 w-5 mt-0.5">
                            <x-icon name="burn" :fill="$popularityColor"></x-icon>
                        </div>
                    </div>
                    <div class="flex h-max align-middle items-center space-x-1.5 rounded-lg">
                        <div class="mt-0.5">
                            {{ $news->popularity }}
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-2">
                    <div class="flex h-max align-middle items-center space-x-1 rounded-lg">
                        <div class="h-5 w-5 mt-0.5">
                            <x-icon name="eye" fill="#fff"></x-icon>
                        </div>
                    </div>
                    <div class="flex h-max align-middle items-center space-x-1.5 rounded-lg">
                        <div class="mt-0.5">
                            {{ $news->views_count }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 content">
            <div class="space-y-2 indent-4">
                {!! $news->getTranslation('content', App::getLocale()) !!}
                <div class="flex w-full justify-center pt-2">
                    @if ($news->video)
                        @php
                            $videoId = null;
                            if (
                                preg_match(
                                    '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
                                    $news->video,
                                    $matches,
                                )
                            ) {
                                $videoId = $matches[1];
                            }
                            $embedUrl = $videoId ? "https://www.youtube.com/embed/{$videoId}?controls=0" : null;
                        @endphp
                        @if ($embedUrl)
                            <iframe class="max-w-full" width="560" height="315" src="{{ $embedUrl }}"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen></iframe>
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
</x-app-layout>
