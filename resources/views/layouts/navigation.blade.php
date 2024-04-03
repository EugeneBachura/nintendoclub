<nav x-data="{ open: false }" class="bg-nav shadow z-10 px-2 sm:px-6">
    {{-- Primary Navigation Menu  --}}
    <div class="mx-auto px-4"> {{--max-w-7xl mx-auto px-4 sm:px-6 lg:px-8--}}
        <div class="flex justify-between h-16 items-center">
            <div class="flex flex-1">
                {{-- Logo --}}
                <div class="shrink-0 flex items-center">
                    @if (app()->getLocale() == "en")
                    <a href="{{route('home')}}" class="flex content-center items-center">
                    @else
                    <a href="@localizedRoute('home.locale', ['locale' => app()->getLocale()])" class="flex content-center items-center">
                    @endif
                        {{-- <div class="h-8 w-8 hidden"><x-application-logo/></div> --}}
                        <div class="text-sm flex items-center"><span class="text-accent text-xl mb-[3px] font-semibold">N</span>intendo <span class="text-accent text-xl mb-[3px] ml-0.5 font-semibold">F</span>an <span class="text-accent text-xl mb-[3px] mx-0.5 font-semibold">.</span> Club</div>
                    </a>
                </div>
            </div>

            <div class=" flex-1 justify-evenly text-sm hidden sm:flex">
                @if (app()->getLocale() == "en")
                    <a href="{{route('news.showAll')}}" class="flex content-center items-center hover:text-accent-hover">{{__('titles.news')}}</a>
                    <a href="{{route('post.showAll')}}" class="flex content-center items-center hover:text-accent-hover">{{__('titles.posts')}}</a>
                    <a href="{{route('game.showAll')}}" class="flex content-center items-center hover:text-accent-hover">{{__('titles.games')}}</a>
                @else
                    <a href="{{route('news.showAll.locale', ['locale' => app()->getLocale()])}}" class="flex content-center items-center hover:text-accent-hover">{{__('titles.news')}}</a>
                    <a href="{{route('post.showAll.locale', ['locale' => app()->getLocale()])}}" class="flex content-center items-center hover:text-accent-hover">{{__('titles.posts')}}</a>
                    <a href="{{route('game.showAll.locale', ['locale' => app()->getLocale()])}}" class="flex content-center items-center hover:text-accent-hover">{{__('titles.games')}}</a>
                @endif
            </div>
            
            <div class="flex items-center content-center justify-end space-x-5 flex-1">
            @if (Auth::user())
                <x-avatar src="{{ auth()->user()->avatar }}" size="w-10 h-10" class="hidden sm:visible mr-2 sm:mr-0 sm:flex sm:items-center"/>
                
                 {{-- Settings Dropdown  --}}
                <div class="hidden sm:flex sm:items-center sm:ml-1.5">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center py-2 border border-transparent text-base leading-4 font-medium rounded-md text-nav_text bg-nav hover:text-nav_text-hover focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->nickname }}</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            
                            <x-dropdown-link :href="@localizedRoute('dashboard')">
                                {{ __('titles.dashboard') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="@localizedRoute('shop')">
                                {{ __('titles.shop') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="@localizedRoute('inventory')">
                                {{ __('titles.inventory') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="@localizedRoute('profile')">
                                {{ __('titles.profile') }}
                            </x-dropdown-link>

                            @role('administrator')
                            <x-dropdown-link href="{{ route('notifications.create') }}">
                                Send Notification
                            </x-dropdown-link>
                            <x-dropdown-link href="{{ route('post.index') }}">
                                {{__('titles.posts')}} editor
                            </x-dropdown-link>
                            @endrole
                            @role('administrator|editor')
                            <x-dropdown-link href="{{ route('news.index') }}">
                                {{__('titles.news')}} editor
                            </x-dropdown-link>
                            @endrole

                            {{-- <x-dropdown-link :href="@localizedRoute('profile.edit')">
                                {{ __('titles.profile_edit') }}
                            </x-dropdown-link> --}}

                            {{-- <x-dropdown-link :href="@localizedRoute('profile.search')">
                                {{ __('titles.profile_search') }}
                            </x-dropdown-link> --}}

                             {{-- Authentication  --}}
                            <form method="POST" action="@localizedRoute('logout')">
                                @csrf

                                <x-dropdown-link href="{{localizedRoute('logout')}}"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('titles.logout') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
                <x-notifications-dropdown />

                 {{-- Hamburger  --}}
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-nav_text hover:text-nav_text-hover hover:bg-nav-hover focus:outline-none focus:bg-nav-hover focus:text-nav_text-hover transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @else
                    <x-button-link href="{{ route('auth.discord') }}" class="bg-discord hover:bg-discord-hover text-discord-text hover:text-light_text-hover text-xs sm:text-sm">
                        {{__('buttons.login_discord')}}
                    </x-button-link>
            @endif
            </div>
        </div>
    </div>

    @auth
     {{-- Responsive Navigation Menu  --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        {{-- <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('titles.dashboard') }}
            </x-responsive-nav-link>
        </div> --}}

         {{-- Responsive Settings Options  --}}
        <div class="pt-4 pb-1 border-t border-gray-200">
                {{-- <div class="px-4">
                    <div class="font-medium text-lg text-content_text">{{ Auth::user()->nickname }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div> --}}

                <div class="mt-3 space-y-1">

                    {{-- <div class="flex flex-1 justify-evenly text-sm"> --}}
                        @if (app()->getLocale() == "en")
                            <x-dropdown-link href="{{route('news.showAll')}}">
                                {{__('titles.news')}}
                            </x-dropdown-link>
                            <x-dropdown-link href="{{route('post.showAll')}}">
                                {{__('titles.posts')}}
                            </x-dropdown-link>
                            <x-dropdown-link href="{{route('game.showAll')}}">
                                {{__('titles.games')}}
                            </x-dropdown-link>
                        @else
                            <x-dropdown-link href="{{route('news.showAll.locale', ['locale' => app()->getLocale()])}}">
                                {{__('titles.news')}}
                            </x-dropdown-link>
                            <x-dropdown-link href="{{route('post.showAll.locale', ['locale' => app()->getLocale()])}}">
                                {{__('titles.posts')}}
                            </x-dropdown-link>
                            <x-dropdown-link href="{{route('game.showAll.locale', ['locale' => app()->getLocale()])}}">
                                {{__('titles.games')}}
                            </x-dropdown-link>
                        @endif
                    {{-- </div> --}}

                    <x-dropdown-link :href="@localizedRoute('dashboard')">
                        {{ __('titles.dashboard') }}
                    </x-dropdown-link>

                    <x-dropdown-link :href="@localizedRoute('shop')">
                        {{ __('titles.shop') }}
                    </x-dropdown-link>

                    <x-dropdown-link :href="@localizedRoute('inventory')">
                        {{ __('titles.inventory') }}
                    </x-dropdown-link>

                    <x-dropdown-link :href="@localizedRoute('profile')">
                        {{ __('titles.profile') }}
                    </x-dropdown-link>

                    @role('administrator')
                    <x-dropdown-link href="{{ route('notifications.create') }}">
                        Send Notification
                    </x-dropdown-link>
                    <x-dropdown-link href="{{ route('post.index') }}">
                        {{__('titles.posts')}} editor
                    </x-dropdown-link>
                    @endrole
                    @role('administrator|editor')
                    <x-dropdown-link href="{{ route('news.index') }}">
                        {{__('titles.news')}} editor
                    </x-dropdown-link>
                    @endrole

                    {{-- <x-dropdown-link :href="@localizedRoute('profile.edit')">
                        {{ __('titles.profile_edit') }}
                    </x-dropdown-link> --}}

                     {{-- Authentication  --}}
                    <form method="POST" action="@localizedRoute('logout')">
                        @csrf

                        <x-responsive-nav-link href="{{localizedRoute('logout')}}"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('titles.logout') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
        </div>
    </div>
    @endauth
</nav>
