@props(['href' => '#', 'type' => 'button', 'disabled' => false])

@php
    $disabledClasses = $disabled ? 'opacity-60 cursor-not-allowed' : '';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => "text-content_text py-2 px-4 rounded font-sans transition ease-in-out duration-150 $disabledClasses", 'role' => $type]) }} @if($disabled) aria-disabled="true" @endif>
    {{ $slot }}
</a>
