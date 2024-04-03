@props(['src', 'size' => 'w-16 h-16'])

<div {{ $attributes->merge(['class' => "$size rounded-full overflow-hidden"]) }}>
    <img src="{{ $src }}" alt="User Avatar" class="object-cover w-full h-full">
</div>