@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'block text-center w-full pl-3 pr-4 py-2 border-l-4 border-indigo-400 text-left text-base font-medium text-indigo-700 bg-indigo-50 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'block text-center w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-discord-text hover:text-accent hover:bg-gray-50 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
