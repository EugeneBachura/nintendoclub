@props(['disabled' => false, 'id' => null, 'name' => 'textarea', 'rows' => 3, 'label' => null, 'description' => null, 'required' => false, 'maxlength' => null, 'value' => '', 'placeholder' => ''])

@if ($label)
    <x-label for="{{ $name }}" value="{{$label}}" />
@endif
<textarea {{ $id ? 'id='.$id : '' }} {{ $disabled ? 'disabled' : '' }} {{ $required ? 'required' : '' }} {{ $maxlength ? 'maxlength='.$maxlength : '' }} name="{{ $name }}" rows="{{ $rows }}" placeholder="{{$placeholder}}" class="mt-1 p-2 w-full rounded-md shadow-sm text-sm opacity-50 bg-background border-0 focus:ring focus:ring-background-hover focus:ring-opacity-100">{{$value}}</textarea>
@if ($description)
    <span class="text-xs text-content_text opacity-50">{{$description}}</span>
@endif