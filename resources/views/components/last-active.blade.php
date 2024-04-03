@props(['dateTime' => null])

@php
$dateTime = $dateTime ? \Carbon\Carbon::parse($dateTime) : null;
$isOnline = $dateTime && $dateTime->diffInMinutes(now()) < 10;
@endphp

<div class="pl-4 bg-background rounded-lg">
    <div class="flex flex-col items-end">
        <div class="flex flex-row items-center space-x-1">
            @if($isOnline)
            <span class="w-3 h-3 bg-online rounded-full"></span>
            <p class="text-sm font-semibold text-content_text opacity-50">Online</p>
            @else
                @if ($dateTime)
                    <span class="w-3 h-3 border-2 border-online  rounded-full"></span>
                    <p class="text-sm font-semibold text-content_text opacity-50">{{ $dateTime->diffForHumans() }}</p>
                @endif
            @endif
        </div>
        {{-- <p class="text-sm text-content_text opacity-50">{{ $dateTime->format('d M Y, H:i') }}</p> --}}
    </div>
</div> 