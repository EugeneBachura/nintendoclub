<div class="flex flex-col space-y-2">
    <div class="text-base">{{ __('profiles.favorite_games') }}:</div>

    <div class="flex space-x-3 flex-wrap">
        @php
            // Limit the games to display
            $displayGames = $favoriteGames->take($limit);
            $emptySlots = max(0, $limit - $displayGames->count());
        @endphp

        {{-- Display games --}}
        @foreach ($displayGames as $game)
            <div x-data="{ open: false }" class="relative">
                @php
                    $locale = app()->getLocale();
                @endphp
                @if ($game->logo_url)
                    <a href="{{ route('game.show', ['alias' => $game->alias]) }}">
                        <div class="bg-cover bg-center w-12 h-12 rounded-md cursor-pointer"
                            style="background-image: url('{{ asset('storage/' . $game->logo_url) }}');"
                            @mouseover="open = true" @mouseleave="open = false"></div>
                    </a>
                @else
                    <div class="w-12 h-12 border-dashed border-2 border-gray-300 rounded-md cursor-pointer"
                        @mouseover="open = true" @mouseleave="open = false"></div>
                @endif

                <div x-show="open"
                    class="absolute -top-10 left-full transform -translate-x-1/2 p-2 text-sm text-content_text bg-background rounded shadow-lg whitespace-nowrap z-10 truncated-text320 whitespace-wrap max-w-[150px]">
                    {{ $game->name }}
                </div>
            </div>
        @endforeach

        {{-- Empty slots if games are less than the limit --}}
        @for ($i = 0; $i < $emptySlots; $i++)
            <div x-data="{ open: false }" class="relative">
                <div class="w-12 h-12 border-dashed border-2 border-gray-300 rounded-md cursor-pointer"
                    @mouseover="open = true" @mouseleave="open = false"></div>

                <div x-show="open"
                    class="absolute -top-10 left-full transform -translate-x-1/2 p-2 text-sm text-content_text bg-background rounded shadow-lg whitespace-nowrap z-10 truncated-text320">
                    {{ __('profiles.empty') }}
                </div>
            </div>
        @endfor
    </div>
</div>
