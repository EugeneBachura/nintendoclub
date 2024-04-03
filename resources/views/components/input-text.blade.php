@props([
    'disabled' => false,
    'name',
    'type' => 'text',
    'label' => null,
    'description' => null,
    'value' => '',
    'required' => false,
    'maxlength' => null
])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-content_text">{{ $label }}</label>
    @endif

    <input type="{{ $type }}"
           id="{{ $name }}"
           name="{{ $name }}"
           value="{{ $value }}"
           {{ $disabled ? 'disabled' : '' }}
           {{ $required ? 'required' : '' }}
           {{ $maxlength ? 'maxlength=' . $maxlength : '' }}
           class="my-2 p-2 w-full rounded-md shadow-sm text-sm opacity-50 bg-background border-0 focus:ring focus:ring-background-hover focus:ring-opacity-100"/>

    @if ($description)
        <p class="text-xs text-content_text opacity-50">{{ $description }}</p>
    @endif
</div>
