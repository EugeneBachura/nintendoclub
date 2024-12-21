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

    <div class="relative pb-6">
        <div class="flex justify-between items-start mt-2 sm:mt-0">
            <h2 class="flex font-semibold text-xl text-content_text leading-tight pb-3 px-0">
                <div class="h-5 w-5 mr-1"><x-icon name="burn"></x-icon></div> {{ __('titles.popular_now') }}
            </h2>

        </div>
        <div class="flex flex-col space-y-4">
            <div class="flex flex-wrap -mx-4 space-y-4 sm:space-y-0">
                @foreach ($topNews as $index => $news)
                    @if ($index < 2)
                        <div class="w-full sm:w-1/2 px-4">
                            <a href="{{ route('news.show', ['alias' => $news->alias, 'lang' => app()->getLocale()]) }}"
                                class="block bg-cover bg-center rounded-lg relative"
                                style="background-image: url('{{ asset('storage/' . $news->image) }}'); padding-top: 50%;">
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
                                                            fill="{{ $news->getPopularityColor() }}"></x-icon>
                                                    </div>
                                                </div>
                                                <div
                                                    class="flex h-max align-middle items-center space-x-1.5 rounded-lg">
                                                    <div class="mt-0.5">
                                                        {{ $news->popularity }}
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

            <div class="flex flex-wrap -mx-4 space-y-4 sm:space-y-0">
                @foreach ($topNews as $index => $news)
                    @if ($index >= 2)
                        <div class="w-full sm:w-1/3 px-4">
                            <a href="{{ route('news.show', ['alias' => $news->alias, 'lang' => app()->getLocale()]) }}"
                                class="block bg-cover bg-center rounded-lg relative"
                                style="background-image: url('{{ asset('storage/' . $news->image) }}'); padding-top: 50%;">
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
                                                            fill="{{ $news->getPopularityColor() }}"></x-icon>
                                                    </div>
                                                </div>
                                                <div
                                                    class="flex h-max align-middle items-center space-x-1.5 rounded-lg">
                                                    <div class="mt-0.5">
                                                        {{ $news->popularity }}
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

    <div class="relative flex flex-wrap pt-0 sm:pt-4 space-y-4 sm:space-y-0">
        <div class="w-full sm:w-1/3 pt-4 sm:pt-0 sm:pr-4 order-2 sm:order-1">
            <div>
                <h2 class="flex font-semibold text-xl text-content_text leading-tight pb-4 px-0">
                    {{ __('titles.last_publications') }}</h2>
                <div class="flex flex-col space-y-4 bg-content-hover w-full p-4 mb-4 rounded-lg">
                    <div class="overflow-x-hidden">
                        <div class="flex flex-wrap">
                            @foreach ($latestNews as $news)
                                <a href="{{ route('news.show', ['alias' => $news->alias, 'lang' => app()->getLocale()]) }}"
                                    class="w-full mb-4">
                                    <div class="flex sm:rounded-lg">
                                        <div class="">
                                            <div class="flex justify-between space-x-4">
                                                <div class="flex items-center">
                                                    <h4 class="font-bold text-sm">
                                                        {{ $news->getTranslation('title', app()->getLocale()) }}
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="text-content_text text-sm mt-1">
                                                {{ $news->getTrimmedContent() }}
                                            </div>
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
        <div class="w-full sm:w-2/3 sm:pr-4 order-1 sm:order-2">
            <h2 class="flex font-semibold text-xl text-content_text leading-tight pb-4 px-0">
                {{ __('titles.users_posts') }}</h2>
            <div class="bg-content-hover p-4 rounded-lg">
                @foreach ($categories as $category)
                    <div class="mb-2">
                        <h3 class="text-lg font-bold text-content_text">{{ $category->{'name_' . app()->getLocale()} }}
                        </h3>
                        <div class="flex -mx-4 flex-wrap">
                            @foreach ($category->posts as $post)
                                <div class="p-4 md:w-1/2">
                                    <div
                                        class="h-full flex flex-col border-opacity-50 shadow border border-content_text rounded-lg overflow-hidden">
                                        @if ($post->image)
                                            <img class="lg:h-48 md:h-36 w-full object-cover object-center"
                                                src="{{ asset('storage/posts_images/' . $post->image) }}"
                                                alt="post image">
                                        @endif
                                        <div class="px-6 pt-5 pb-3 flex flex-col flex-1 justify-between">
                                            <div>
                                                <h2
                                                    class="tracking-widest text-xs title-font font-medium opacity-50 mb-1">
                                                    {{ $post->category->name }}
                                                </h2>
                                                <h1 class="text-lg font-bold mb-3">{{ $post->title }}</h1>
                                                <p class="text-sm">{{ $post->trimmedContent }}</p>
                                            </div>
                                            <div class="flex items-center justify-between flex-wrap mt-2">
                                                <div class="flex space-x-3">
                                                    @php $isLiked = $post->likes->contains('user_id', Auth::id()); @endphp
                                                    <x-icon-with-text icon="like"
                                                        fill="{{ $isLiked ? '#ff3b3c' : '#252525' }}"
                                                        tooltip="{{ __('interfaces.likes') }}">
                                                        {{ $post->likes->count() }}
                                                    </x-icon-with-text>
                                                    <x-icon-with-text icon="eye" fill="#ffffff"
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
                                                    <svg class="w-4 h-4 ml-2" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2" fill="none"
                                                        stroke-linecap="round" stroke-linejoin="round">
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
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</x-app-layout>
