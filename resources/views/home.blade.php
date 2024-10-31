<x-app-layout>
    <x-slot name="header"></x-slot>
    <x-slot name="title">Nintendo Fan Club</x-slot>
    <x-slot name="seo_description">
        @if (app()->getLocale() == 'en')
            Your unique resource for everything related to the world of Nintendo. Latest news, interactive games,
            discussions, exclusive events, and much more. Join our global community of fans and dive into the exciting
            world of Nintendo today!
        @endif
        @if (app()->getLocale() == 'ru')
            Ваш уникальный ресурс для всего, что связано с миром Nintendo. Последние новости, интерактивные игры,
            обсуждения, эксклюзивные мероприятия и многое другое. Присоединяйтесь к нашему глобальному сообществу
            фанатов и погрузитесь в увлекательный мир Nintendo сегодня!
        @endif
        @if (app()->getLocale() == 'pl')
            Twój unikalny zasób wszystkiego, co związane ze światem Nintendo. Najnowsze wiadomości, interaktywne gry,
            dyskusje, ekskluzywne wydarzenia i wiele więcej. Dołącz do naszej globalnej społeczności fanów i zanurz się
            w ekscytujący świat Nintendo już dzisiaj!
        @endif
    </x-slot>
    <x-slot name="seo_keywords">
        @if (app()->getLocale() == 'en')
            Nintendo, Nintendo community, Nintendo news, Nintendo games, Nintendo fan club, Gaming discussions,
            Exclusive gaming events, Online Nintendo world, Nintendo fan engagement, Video game culture, Gaming updates,
            Critic
        @endif
        @if (app()->getLocale() == 'ru')
            Nintendo, Сообщество Nintendo, Новости Nintendo, игры Nintendo, Клуб фанатов Nintendo, Игровые обсуждения,
            Гик, Онлайн мир Nintendo, фанатов Nintendo, Культура видеоигр, Обновления в играх, Критика, Оценка игр
        @endif
        @if (app()->getLocale() == 'pl')
            Nintendo, Społeczność Nintendo, Wiadomości Nintendo, gry Nintendo, Klub fanów Nintendo, Dyskusje o grach,
            wydarzenia gamingowe, Świat Nintendo online, Zaangażowanie fanów Nintendo, Kultura gier wideo, Aktualizacje
            gier
        @endif
    </x-slot>
    <x-slot name="main_img_url">
        {{ asset('main.jpg') }}
    </x-slot>
    <x-slot name="scriot_loadr">
        var discord_iframe = document.getElementById('discord_iframe');
        discord_iframe.src = "https://discord.com/widget?id=691192063731040256&theme=dark";
    </x-slot>

    @php
        function getPopularityColor($popularity)
        {
            $maxPopularity = 1000;
            $intensity = $popularity / $maxPopularity;
            $red = 255;
            $green = 255 * (1 - $intensity);
            $blue = 0;
            return "rgb($red, $green, $blue)";
        }
    @endphp

    <div class="relative pb-6">
        <div class="flex justify-between items-start">
            <h2 class="flex font-semibold text-xl text-content_text leading-tight pb-3 px-0">
                <div class="h-5 w-5 mr-1"><x-icon name="burn"></x-icon></div> {{ __('titles.popular_now') }}
            </h2>

            <div x-data="{ open: false }" class="flex justify-end pb-3 -mt-1">
                <div @click="open = !open"
                    class="cursor-pointer ring-1 ring-content_text ring-opacity-25 flex items-center justify-center p-1 bg-content-hover rounded-lg hover:bg-content-hover">
                    <div class="h-6 w-6 mr-1"><x-icon name="language"></x-icon></div>
                    <div class="font-bold text-sm mr-1">{{ __('interfaces.language') }}</div>
                </div>

                <div x-show="open" @click.away="open = false"
                    class="absolute z-50 mt-6 w-24 rounded-md shadow-lg origin-top-right right-0">
                    <div class="rounded-md ring-1 ring-content_text ring-opacity-25 py-1 bg-content">
                        @foreach (['en', 'ru', 'pl'] as $lang)
                            @if ($lang == 'en')
                                <a href="{{ route('home') }}"
                                    class="block w-full px-4 py-1 text-left text-sm leading-5 text-content_text hover:bg-content-hover focus:outline-none focus:bg-content-hover transition duration-150 ease-in-out">
                                    English
                                </a>
                            @else
                                <a href="{{ route('home.locale', ['locale' => $lang]) }}"
                                    class="block w-full px-4 py-1 text-left text-sm leading-5 text-content_text hover:bg-content-hover focus:outline-none focus:bg-content-hover transition duration-150 ease-in-out">
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
        </div>
        <div class="flex flex-col space-y-4">
            {{-- <!-- Первый ряд: первые две новости --> --}}
            <div class="flex flex-wrap -mx-4 space-y-4 sm:space-y-0">
                @foreach ($topNews as $index => $news)
                    @if ($index < 2)
                        <div class="w-full sm:w-1/2 px-4">
                            @if (app()->getLocale() == 'en')
                                <a href="@localizedRoute('news.show', ['alias' => $news->alias])" class="block bg-cover bg-center rounded-lg relative"
                                    style="background-image: url('{{ asset('storage/' . $news->image) }}'); padding-top: 50%;">
                                @else
                                    <a href="@localizedRoute('news.show.locale', ['alias' => $news->alias, 'locale' => app()->getLocale()])" class="block bg-cover bg-center rounded-lg relative"
                                        style="background-image: url('{{ asset('storage/' . $news->image) }}'); padding-top: 50%;">
                            @endif
                            <div class="w-full h-full bg-black absolute top-0 left-0 opacity-30 rounded-lg"></div>
                            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-70 rounded-b-lg">
                                <div class="w-full">
                                    <div class="flex justify-between space-x-4 w-full p-3">
                                        <div class="flex items-center">
                                            <h2
                                                class="font-semibold text-sx sm:text-base text-color_text leading-tight z-10 uppercase">
                                                {{ $news->getTranslation('title', app()->getLocale()) }}
                                            </h2>
                                        </div>
                                        <div class="pl-3 md:pl-0 flex justify-end space-x-1 items-center">
                                            <div class="flex h-max align-middle items-center space-x-1 rounded-lg">
                                                <div class="h-5 w-5 mt-0.5">
                                                    <x-icon name="burn"
                                                        fill="{{ getPopularityColor($news->popularity + 400) }}"></x-icon>
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
                                                    {{ $news->views_count }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- <!-- Второй ряд: оставшиеся три новости --> --}}
            <div class="flex flex-wrap -mx-4 space-y-4 sm:space-y-0">
                @foreach ($topNews as $index => $news)
                    @if ($index >= 2)
                        <div class="w-full sm:w-1/3 px-4">
                            @if (app()->getLocale() == 'en')
                                <a href="@localizedRoute('news.show', ['alias' => $news->alias])" class="block bg-cover bg-center rounded-lg relative"
                                    style="background-image: url('{{ asset('storage/' . $news->image) }}'); padding-top: 50%;">
                                @else
                                    <a href="@localizedRoute('news.show.locale', ['alias' => $news->alias, 'locale' => app()->getLocale()])" class="block bg-cover bg-center rounded-lg relative"
                                        style="background-image: url('{{ asset('storage/' . $news->image) }}'); padding-top: 50%;">
                            @endif
                            <div class="w-full h-full bg-black absolute top-0 left-0 opacity-30 rounded-lg"></div>
                            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-70 rounded-b-lg">
                                <div class="w-full">
                                    <div class="flex justify-between space-x-4 w-full p-3">
                                        <div class="flex items-center">
                                            <h2
                                                class="font-semibold text-base text-color_text leading-tight z-10 uppercase">
                                                {{ $news->getTranslation('title', app()->getLocale()) }}
                                            </h2>
                                        </div>
                                        <div class="pl-3 md:pl-0 flex justify-end space-x-1 items-center">
                                            <div class="flex h-max align-middle items-center space-x-1 rounded-lg">
                                                <div class="h-5 w-5 mt-0.5">
                                                    <x-icon name="burn"
                                                        fill="{{ getPopularityColor($news->popularity + 400) }}"></x-icon>
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
                                                    {{ $news->views_count }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

    </div>

    <div class="relative flex flex-wrap pt-4 space-y-4 sm:space-y-0">
        <div class="w-full sm:w-1/3 sm:pr-4">
            <div>
                <h2 class="flex font-semibold text-xl text-content_text leading-tight pb-4 px-0">
                    {{ __('titles.last_publications') }}</h2>
                <div class="flex flex-col space-y-4 bg-content-hover w-full p-4 mb-4 rounded-lg">
                    <div class="overflow-x-hidden">
                        <div class="flex flex-wrap">
                            @foreach ($latestNews as $news)
                                @if (app()->getLocale() == 'en')
                                    <a href="@localizedRoute('news.show', ['alias' => $news->alias])" class="w-full mb-4">
                                    @else
                                        <a href="@localizedRoute('news.show.locale', ['alias' => $news->alias, 'locale' => app()->getLocale()])" class="w-full mb-4">
                                @endif
                                <div class="flex sm:rounded-lg">
                                    <div class="">
                                        <div class="flex justify-between space-x-4">
                                            <div class="flex items-center">
                                                <h4 class="font-bold text-sm">
                                                    {{ $news->getTranslation('title', app()->getLocale()) }}</h4>
                                            </div>
                                        </div>
                                        @php
                                            $content = $news->getTranslation('content', app()->getLocale());
                                            $limit = 100;
                                            if (app()->getLocale() == 'en') {
                                                $limit = 90;
                                            }
                                            if (app()->getLocale() == 'ru') {
                                                $limit = 300;
                                            }
                                            if (app()->getLocale() == 'pl') {
                                                $limit = 100;
                                            }
                                            $ending = '...';

                                            if (mb_strlen($content) > $limit) {
                                                $cutOff = mb_strripos(mb_substr($content, 0, $limit), ' ');
                                                $trimmed = mb_substr($content, 0, $cutOff) . $ending;
                                            } else {
                                                $trimmed = $content;
                                            }
                                        @endphp
                                        <div class="text-content_text text-sm mt-1">{!! $trimmed !!}</div>
                                    </div>
                                </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full">
                <iframe id="discord_iframe" src="" class="w-full" height="500" allowtransparency="true"
                    frameborder="0"
                    sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts"></iframe>
            </div>
        </div>
        <div class="sm:w-2/3">
            <h2 class="flex font-semibold text-xl text-content_text leading-tight pb-4 px-0">
                {{ __('titles.last_publications') }}</h2>
            <div class="bg-content-hover p-4 rounded-lg">

            </div>
        </div>
    </div>

</x-app-layout>
