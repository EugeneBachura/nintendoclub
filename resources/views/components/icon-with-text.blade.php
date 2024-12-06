@props(['icon', 'tooltip', 'text_size' => 'lg', 'fill' => '#ffffff'])

<div {{ $attributes->merge(['class' => "relative py-2 flex items-center space-x-1 text-$text_size rounded cursor-pointer"]) }}
    x-data="{ open: false }" @mouseover="open = true" @mouseout="open = false">
    <span class="w-5 h-5"><x-icon name='{{ $icon }}' fill='{{ $fill }}' /></span>
    <span>{{ $slot }}</span>

    @isset($tooltip)
        <div x-show="open"
            class="absolute -top-2 right-16 transform translate-x-full -translate-y-full mt-2 p-2 text-sm text-content_text bg-background rounded shadow-lg whitespace-nowrap z-10">
            {{ $tooltip }}
        </div>
    @endisset
</div>
