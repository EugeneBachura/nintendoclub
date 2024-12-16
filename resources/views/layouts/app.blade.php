<!DOCTYPE html>
@isset($lang)
    <html lang="{{ $lang }}">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endisset

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @isset($title)
        <title>{{ $title }}{{-- ' — '.config('app.name') --}}</title>
        <meta property="og:title" content="{{ $title }}">
    @endisset
    @isset($seo_description)
        <meta name="description" content="{{ $seo_description }}">
        <meta property="og:description" content="{{ $seo_description }}">
    @endisset
    @isset($seo_keywords)
        <meta name="keywords" content="{{ $seo_keywords }}">
    @endisset
    @isset($main_img_url)
        <meta property="og:image" content="{{ $main_img_url }}">
    @endisset
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <link rel="icon" type="image/x-icon" href="/favicon2.ico">

    @foreach (config('localization.supported_locales') as $lang)
        @if (Route::current())
            @php
                $routeParameters = array_merge(Route::current()->parameters() ?? [], ['lang' => $lang]);
            @endphp
            <link rel="alternate" hreflang="{{ $lang }}"
                href="{{ route(Route::currentRouteName(), $routeParameters) }}" />
        @else
            <link rel="alternate" hreflang="{{ $lang }}" href="/" />
        @endif
    @endforeach

    <meta name="yandex-verification" content="ee55c0e31dad4115" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased bg-background">
    @unlessrole('ban')
        <div class="min-h-screen min-w-[357px] bg-background text-color_text flex flex-col ">
            @include('layouts.navigation')
            <div class="flex flex-1 justify-center space-x-4 px-2 sm:px-6">
                @if (session('rewards'))
                    @php
                        $rewards = session('rewards');
                    @endphp
                    @if ($rewards)
                        <x-reward-notification :rewards='$rewards' />
                    @endif
                @endif
                @if (session('success'))
                    <div x-data="{ open: true }" x-show="open"
                        class="bg-successfully text-successfully-text p-4 rounded-md shadow-lg absolute z-20 top-20">
                        <div class="flex justify-between items-start relative pr-2">
                            <div class="flex flex-col">
                                <strong class="font-bold">Success!</strong>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button class="absolute -right-2 -top-4 text-xl" @click="open = false">&times;</button>
                        </div>
                    </div>
                @endif
                @if (session('status'))
                    <div x-data="{ open: true }" x-show="open"
                        class="bg-successfully text-successfully-text p-4 rounded-md shadow-lg absolute z-20 top-20">
                        <div class="flex justify-between items-start relative pr-2">
                            <div class="flex flex-col">
                                <strong class="font-bold">Status</strong>
                                <span>{{ session('status') }}</span>
                            </div>
                            <button class="absolute -right-2 -top-4 text-xl" @click="open = false">&times;</button>
                        </div>
                    </div>
                @endif
                @isset($errors)
                    @if ($errors->any())
                        <div x-data="{ open: true }" x-show="open"
                            class="bg-error text-error-text p-4 rounded-md shadow-lg absolute z-20 top-20">
                            <div class="flex justify-between items-start relative pr-2">
                                <div class="flex flex-col">
                                    <strong class="font-bold">Error!</strong>
                                    <span>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </span>
                                </div>
                                <button class="absolute -right-2 -top-4 text-xl" @click="open = false">&times;</button>
                            </div>
                        </div>
                    @endif
                @endisset

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto content">
                    <div class="py-8">
                        <div
                            class="max-w-full min-w-[340px] w-[1200px]
                            @isset($slim)
                            w-[800px]
                            @endisset
                             mx-auto">
                            @isset($header)
                                <header class="pb-6 px-0 md:px-6">
                                    {{ $header }}
                                </header>
                                <div class="bg-content overflow-hidden shadow-sm sm:rounded-lg p-3 sm:p-6">
                                    {{ $slot }}
                                </div>
                            @endisset
                            @isset($header_img)
                                <header class="">
                                    {{ $header_img }}
                                </header>
                                <div class="bg-content overflow-hidden shadow-sm sm:rounded-b-lg p-6">
                                    {{ $slot }}
                                </div>
                            @endisset
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div id="cookie-notification" style="display: none"
            class="items-center text-sm fixed bottom-0 left-0 w-full bg-background text-content_text z-10 p-2 sm:px-6 sm:py-3  justify-between">
            <p>
                {{ __('interfaces.cookie') }}
                <a href="{{ localized_url('terms') }}"
                    class="hover:text-content_text-hover underline">{{ __('titles.legal_information') }}</a>
            </p>
            <button id="accept-cookies" class="bg-content-hover rounded-lg px-3 py-1">OK</button>
        </div>
    @else
        BAN
    @endunlessrole
    <script>
        function updateActivity() {
            fetch('/update-activity', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                })
                .then(response => response.json())
                .then(data => console.log(data));
        }
        setInterval(updateActivity, 300000);
        updateActivity();

        document.addEventListener("DOMContentLoaded", function() {
            var cookieNotification = document.getElementById("cookie-notification");
            var acceptCookiesButton = document.getElementById("accept-cookies");

            if (!getCookie("cookie_accepted")) {
                cookieNotification.style.display = "flex";
            }

            acceptCookiesButton.addEventListener("click", function() {
                cookieNotification.style.display = "none";
                setCookie("cookie_accepted", "true", 365);
            });
        });

        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }

        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(";");
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) === " ") c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }
    </script>
    @isset($scripts)
        {{ $script }}
    @endisset
    @livewireScripts
</body>
@include('layouts.footer')
<!-- Fonts -->
<link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Playfair+Display:wght@400;700&display=swap"
    rel="stylesheet" type="text/css" media="print" onload="this.media='all'">
@isset($scriot_loadr)
    <script>
        window.addEventListener('load', function() {
            {{ $scriot_loadr }}
        });
    </script>
@endisset

</html>
