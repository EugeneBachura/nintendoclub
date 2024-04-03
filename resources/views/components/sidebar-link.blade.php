@props(['href' => '#', 'icon'])
<a href="{{ $href }}" class="hover:bg-background-hover px-4 py-2 flex items-center space-x-3 text-xl rounded">
    <span class="w-6 h-6"><x-icon name='{{$icon}}' /></span>
    <span x-show="open">{{ $slot }}</span>
</a>