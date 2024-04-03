@props(['item_img' => null, 'name' => null, 'quantity' => 1])

    <div x-data="{ open: false }" class="relative border-2 border-gray-300 border-opacity-25 rounded-md cursor-pointer">
        @if ($item_img)
            <img src="{{$item_img}}" alt="{{ $name }}" width="50" height="50" class="rounded-md cursor-pointer"
            @mouseover="open = true" @mouseleave="open = false">
        @else
            <div class="w-12 h-12 border-dashed border-2 border-gray-300 rounded-md cursor-pointer"
            @mouseover="open = true" @mouseleave="open = false"></div>
        @endif

        <div class="text-xs absolute top-0 right-0">@if ($quantity > 1)
            x{{$quantity}}
        @endif</div>
             
        <div x-show="open" 
             class="absolute -top-10 left-full transform -translate-x-1/2 p-2 text-sm text-content_text bg-background rounded shadow-lg whitespace-nowrap z-10 truncated-text320 whitespace-wrap max-w-[150px]">
            {{ $name }} 
            @if ($quantity > 1)
                (x{{$quantity}})
            @endif
        </div>
    </div>