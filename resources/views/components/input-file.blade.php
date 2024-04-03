@props([
    'name',
    'label' => null,
    'description' => null,
    'required' => false,
    'accept' => null,
])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-content_text">{{ $label }}</label>
    @endif

    <input type="file"
           id="{{ $name }}"
           name="{{ $name }}"
           {{ $required ? 'required' : '' }}
           {{ $accept ? 'accept=' . $accept : '' }}
           class="my-2 p-2 w-full rounded-md shadow-sm text-sm opacity-50 bg-background border-0 focus:ring focus:ring-background-hover focus:ring-opacity-100"/>

    @if ($description)
        <p class="text-xs text-content_text opacity-50">{{ $description }}</p>
    @endif
</div>
