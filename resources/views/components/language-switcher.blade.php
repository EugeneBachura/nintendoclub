<div class="language-switcher" x-data="{ open: false }">
    <div @click="open = !open"
        class="cursor-pointer ring-1 ring-content_text ring-opacity-25 flex items-center justify-center py-1 px-2 border-2 border-content-hover bg-content-hover rounded hover:bg-content-hover">
        <div class="h-6 w-6 mr-0 sm:mr-1"><x-icon name="language"></x-icon></div>
        <div class="font-bold text-sm mr-0 sm:mr-1 hidden sm:flex">{{ __('interfaces.language') }}</div>
    </div>

    <div x-show="open" @click.away="open = false" class="absolute z-50 mt-6 w-24 rounded-md shadow-lg origin-top-right">
        <div class="rounded-md ring-1 ring-content_text ring-opacity-25 py-1 bg-content">
            @foreach (['en', 'ru', 'pl'] as $lang)
                @php
                    $routeName = Route::currentRouteName();
                    $routeParameters = Route::current()->parameters();

                    $routeParameters['lang'] = $lang;

                    $url = route($routeName, $routeParameters);
                @endphp

                <a href="{{ $url }}"
                    class="block w-full px-4 py-1 text-left text-sm leading-5 text-content_text hover:bg-content-hover focus:outline-none focus:bg-content-hover transition duration-150 ease-in-out">
                    @if ($lang == 'en')
                        English
                    @elseif($lang == 'ru')
                        Русский
                    @elseif($lang == 'pl')
                        Polski
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>
