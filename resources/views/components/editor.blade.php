@props([
    'name' => 'content',
    'rows' => 3,
    'label' => null,
    'description' => null,
    'required' => false,
    'maxlength' => null,
    'value' => ''
])


<div>
    @if ($label)
        <label for="editor-{{ $name }}" class="block text-sm font-medium text-content_text mb-2">{{ $label }}</label>
    @endif
    <textarea type="hidden" name="{{ $name }}" id="{{ $name }}" class="editor">{!!$value!!}</textarea>
    @if ($description)
        <p class="text-xs text-content_text opacity-50 mt-2">{{ $description }}</p>
    @endif

    <style>
        #{{$name}} ~ .ck-editor .ck-content {
            min-height: {{ $rows * 20 + 40 }}px; 
        }
    </style>
</div>