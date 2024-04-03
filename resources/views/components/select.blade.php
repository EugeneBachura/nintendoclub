@props([
    'disabled' => false,
    'name',
    'label' => null,
    'description' => null,
    'required' => false,
    'options' => [],
    'selected' => null,
])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-content_text">{{ $label }}</label>
    @endif

    <select 
           id="{{ $name }}"
           name="{{ $name }}"
           {{ $disabled ? 'disabled' : '' }}
           {{ $required ? 'required' : '' }}
           class="my-2 p-2 w-full rounded-md shadow-sm text-sm opacity-50 bg-background border-0 focus:ring focus:ring-background-hover focus:ring-opacity-100">
        @foreach ($options as $value => $text)
            <option value="{{ $value }}" {{ (string)$value === (string)$selected ? 'selected' : '' }}>{{ $text }}</option>
        @endforeach
    </select>

    @if ($description)
        <p class="text-xs text-content_text opacity-50">{{ $description }}</p>
    @endif
</div>