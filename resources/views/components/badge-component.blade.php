@props(['badges' => [], 'limit' => 6])

<div class="text-base flex">{{ __('profiles.badges') }}:</div>

<div class="flex flex-wrap flex-col sm:flex-row">
    @php
        // Limit the badges to display
        $displayBadges = array_slice($badges, 0, $limit);
        $emptySlots = max(0, $limit - count($displayBadges));
    @endphp

    {{-- Display badges --}}
    @foreach ($displayBadges as $badge)
        <div x-data="{ open: false }" class="relative mt-2 mr-3">
            <img src="{{ $badge->icon_url }}" alt="{{ $badge->name }}" width="50" height="50"
                class="rounded-full cursor-pointer" @mouseover="open = true" @mouseleave="open = false">

            {{-- Tooltip --}}
            <div x-show="open"
                class="absolute -top-10 left-full transform -translate-x-1/2 p-2 text-sm text-content_text bg-background rounded shadow-lg whitespace-nowrap z-10">
                {{ $badge->name }}
            </div>
        </div>
    @endforeach

    {{-- Display empty slots if badges are less than the limit --}}
    @for ($i = 0; $i < $emptySlots; $i++)
        <div x-data="{ open: false }" class="relative mt-2 mr-3">
            <div class="w-12 h-12 border-2 border-background bg-background rounded-full cursor-pointer"
                @mouseover="open = true" @mouseleave="open = false"></div>

            {{-- Tooltip for Empty --}}
            <div x-show="open"
                class="absolute -top-10 left-full transform -translate-x-1/2 p-2 text-sm text-content_text bg-background rounded shadow-lg whitespace-nowrap z-10">
                {{ __('profiles.empty') }}
            </div>
        </div>
    @endfor
</div>
