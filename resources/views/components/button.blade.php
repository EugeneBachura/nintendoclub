@props(['type' => 'submit', 'disabled' => false])

@php
    $disabledClasses = $disabled ? 'opacity-60 cursor-not-allowed' : '';
@endphp

<button {{ $attributes->merge(['class' => "text-white py-2 px-4 rounded font-sans transition ease-in-out duration-150 $disabledClasses", 'type' => $type]) }} @if($disabled) disabled @endif>
    {{ $slot }}
</button>
