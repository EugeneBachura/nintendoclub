@props(['pokemons' => [], 'limit' => 5])

<div class="flex flex-col space-y-2">
    <div class="text-base">{{ __('profiles.pokemons') }}:</div>
    <div class="flex flex-wrap space-x-3">
        @php
            $displayPokemons = array_slice($pokemons, 0, $limit);
            $emptySlots = max(0, $limit - count($displayPokemons));
        @endphp

        @foreach ($pokemons as $pokemon)
            <div x-data="{ open: false }" class="relative">
                <img src="{{ $pokemon->image_url }}" alt="{{ $pokemon->name }}" width="50" height="50"
                    class="rounded-md cursor-pointer" @mouseover="open = true" @mouseleave="open = false">

                {{-- Tooltip --}}
                <div x-show="open"
                    class="absolute -top-10 left-full transform -translate-x-1/2 p-2 text-sm bg-white rounded shadow-lg whitespace-nowrap z-10">
                    {{ $pokemon->name }}
                </div>
            </div>
        @endforeach

        @for ($i = 0; $i < $emptySlots; $i++)
            <div x-data="{ open: false }" class="relative">
                <div class="w-12 h-12 border-dashed border-2 border-gray-300 rounded-full cursor-pointer"
                    @mouseover="open = true" @mouseleave="open = false"></div>

                {{-- Tooltip for Empty --}}
                <div x-show="open"
                    class="absolute -top-10 left-full transform -translate-x-1/2 p-2 text-sm text-content_text bg-background rounded shadow-lg whitespace-nowrap z-10">
                    {{ __('profiles.empty') }}
                </div>
            </div>
        @endfor
    </div>
</div>
