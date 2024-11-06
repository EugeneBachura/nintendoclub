<x-app-layout>
    <x-slot name="slim"></x-slot>
    <x-slot name="header">
        <div class="flex justify-between">
            <div class="flex items-start content-center">
                <x-avatar src="{{ $avatar }}" size="w-16 h-16" class="sm:flex sm:items-center sm:mr-6 mr-3" />
                <div class="py-2">
                    <h2 class="font-semibold text-xl text-color_text leading-tight"
                        style="color:{{ $design->nickname_color ?? '#ffffff' }}">
                        {{ $nickname }}
                    </h2>
                    <div class="text-sm opacity-50">
                        {{ '@' . $name }}
                    </div>
                </div>
            </div>
            <div class="flex flex-col h-16 items-start content-center justify-end py-[0.7rem] max-w-[420px] space-x-4">
                <x-last-active dateTime="{{ $last_active_at }}"></x-last-active>
            </div>
        </div>
    </x-slot>

    <div class="text-color_text space-y-5">
        <x-level-bar :currentExp="$experience" :requiredExp="$experienceToNextLevel">
            {{ $level }} {{ __('profiles.level') }}
        </x-level-bar>
        <div class="space-y-5 flex flex-col items-center sm:items-start">
            <div class="flex flex-row">
                <div class="flex flex-row space-x-5">
                    <x-icon-with-text icon="coins" tooltip="{{ __('profiles.coins') }}">
                        {{ $coins }}
                    </x-icon-with-text>
                    <x-icon-with-text icon="diamond" tooltip="{{ __('profiles.premium_points') }}">
                        {{ $premium_points }}
                    </x-icon-with-text>
                    <x-icon-with-text icon="star" tooltip="{{ __('profiles.stars') }}">
                        0
                    </x-icon-with-text>
                    {{-- <x-icon-with-text icon="pokeball" tooltip="{{__('profiles.pokeballs')}}">
                        0
                    </x-icon-with-text> --}}
                    {{-- <x-icon-with-text icon="coin" tooltip="Reputation Points">
                        {{Auth::user()->profile->reputation_count}}
                    </x-icon-with-text> --}}
                </div>
            </div>
            <div class="flex flex-wrap flex-col sm:w-full space-y-5 md:flex-row md:space-x-5 md:space-y-0">
                {{-- <div class="flex-1">
                    <x-pokemon-component :pokemons="$pokemons" :limit="5" />
                </div> --}}
                <div class="flex-1">
                    <x-favorite-games :user-id="$id" :limit="5" />
                </div>
                <div class="flex-1">
                    <x-badge-component :badges="$badges" :limit="6" />
                </div>
            </div>
            <div class="flex items-start content-center max-w-[420px] text-sm opacity-50">
                {{ $profile_description }}
            </div>
        </div>
        @if (Auth::user()->id == $id)
            <div class="flex justify-center sm:justify-end">
                <x-button-link
                    class="bg-grey text-grey-text hover:bg-grey-hover px-2 py-1 mb-4 sm:mb-0 flex items-center flex-row-reverse"
                    href="{{ route('profile.edit') }}">
                    <x-icon-with-text icon="write" text_size="base"
                        style="padding: 0">{{ __('titles.profile_edit') }}</x-icon-with-text>
                </x-button-link>
            </div>
        @endif
    </div>
    @if (auth()->check() && auth()->id() === 1)
        <form action="{{ url('/admin/add-exp') }}" method="GET">
            @csrf
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add Experience
            </button>
        </form>
    @endif

</x-app-layout>
