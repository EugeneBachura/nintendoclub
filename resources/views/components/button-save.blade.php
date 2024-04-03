@props(['text' => __('buttons.save'), 'form' => null, 'disabled' => false])

<button @if($disabled) disabled @endif
    @if($form) form="{{$form}}" @endif
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-success border border-transparent rounded-md font-semibold text-xs text-success-text uppercase tracking-widest hover:bg-success-hover active:bg-success-900 focus:outline-none focus:border-success-900 focus:ring focus:ring-success-300 disabled:opacity-25 transition']) }}>
    {{ $text }}
</button>