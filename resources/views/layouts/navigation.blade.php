<nav x-data="{ open: false }" class="bg-nav shadow z-10 px-2 sm:px-6">
    {{-- Primary Navigation Menu --}}
    <div class="mx-auto px-4">
        <div class="flex justify-between h-20 items-center">
            <div class="flex flex-1">
                <div class="shrink-0 flex items-center">
                    <a href="{{ localized_url('home') }}" class="flex content-center items-center">
                        <div class="text-base flex items-center">
                            <span class="text-accent text-xl mb-[3px] font-semibold">N</span>intendo
                            <span class="text-accent text-xl mb-[3px] ml-0.5 font-semibold">F</span>an
                            <span class="text-accent text-xl mb-[3px] mx-0.5 font-semibold">.</span> Club
                        </div>
                    </a>
                </div>
            </div>

            <div class="flex-1 justify-evenly text-base hidden sm:flex">
                <a href="{{ localized_url('news.showAll') }}"
                    class="flex content-center items-center hover:text-accent-hover">
                    {{ __('titles.news') }}
                </a>
                <a href="{{ localized_url('post.showAll') }}"
                    class="flex content-center items-center hover:text-accent-hover">
                    {{ __('titles.posts') }}
                </a>
                <a href="{{ localized_url('game.showAll') }}"
                    class="flex content-center items-center hover:text-accent-hover">
                    {{ __('titles.games') }}
                </a>
            </div>

            {{-- Right Side --}}
            <div class="flex items-center content-center justify-end space-x-3 flex-1 sm:space-x-5">
                <x-language-switcher />

                @if (Auth::check())
                    <livewire:avatar src="{{ auth()->user()->avatar }}" size="w-10 h-10"
                        class="hidden sm:flex sm:items-center" />

                    <div class="hidden sm:flex sm:items-center sm:ml-1.5">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center py-2 text-base font-medium rounded-md text-nav_text hover:text-nav_text-hover focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->nickname }}</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link href="{{ localized_url('dashboard') }}">
                                    {{ __('titles.dashboard') }}
                                </x-dropdown-link>
                                <x-dropdown-link href="{{ localized_url('shop.index') }}">
                                    {{ __('titles.shop') }}
                                </x-dropdown-link>
                                <x-dropdown-link href="{{ localized_url('inventory') }}">
                                    {{ __('titles.inventory') }}
                                </x-dropdown-link>
                                <x-dropdown-link href="{{ localized_url('profile') }}">
                                    {{ __('titles.profile') }}
                                </x-dropdown-link>

                                @role('administrator')
                                    <x-dropdown-link href="{{ localized_url('notifications.create') }}">
                                        {{ __('Send Notification') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link href="{{ localized_url('post.index') }}">
                                        {{ __('titles.posts') }} {{ __('editor') }}
                                    </x-dropdown-link>
                                @endrole
                                @role('administrator|editor')
                                    <x-dropdown-link href="{{ localized_url('news.index') }}">
                                        {{ __('titles.news') }} {{ __('editor') }}
                                    </x-dropdown-link>
                                @endrole

                                <form method="POST" action="{{ localized_url('logout') }}">
                                    @csrf
                                    <x-dropdown-link href="{{ localized_url('logout') }}"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('titles.logout') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <x-notifications-dropdown />

                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="open = ! open"
                            class="inline-flex items-center justify-center p-2 rounded-md text-nav_text hover:text-nav_text-hover focus:outline-none transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @else
                    <x-button-link href="{{ route('auth.discord') }}"
                        class="bg-discord hover:bg-discord-hover text-discord-text hover:text-light_text-hover text-xs sm:text-sm">
                        {{ __('buttons.login_discord') }}
                    </x-button-link>
                @endif
            </div>
        </div>
    </div>

    {{-- Responsive Navigation Menu --}}
    @auth
        <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
            <div class="pt-2 space-y-1">
                <x-responsive-nav-link href="{{ localized_url('news.showAll') }}">
                    {{ __('titles.news') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ localized_url('post.showAll') }}">
                    {{ __('titles.posts') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ localized_url('game.showAll') }}">
                    {{ __('titles.games') }}
                </x-responsive-nav-link>
            </div>

            <div class="">
                <div class="mt-1 mb-2 space-y-1">
                    <x-responsive-nav-link href="{{ localized_url('dashboard') }}">
                        {{ __('titles.dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ localized_url('shop.index') }}">
                        {{ __('titles.shop') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ localized_url('inventory') }}">
                        {{ __('titles.inventory') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ localized_url('profile') }}">
                        {{ __('titles.profile') }}
                    </x-responsive-nav-link>

                    @role('administrator')
                        <x-responsive-nav-link href="{{ localized_url('notifications.create') }}">
                            {{ __('Send Notification') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ localized_url('post.index') }}">
                            {{ __('titles.posts') }} {{ __('editor') }}
                        </x-responsive-nav-link>
                    @endrole
                    @role('administrator|editor')
                        <x-responsive-nav-link href="{{ localized_url('news.index') }}">
                            {{ __('titles.news') }} {{ __('editor') }}
                        </x-responsive-nav-link>
                    @endrole

                    <form method="POST" action="{{ localized_url('logout') }}">
                        @csrf
                        <x-responsive-nav-link href="{{ localized_url('logout') }}"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('titles.logout') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    @endauth
</nav>
