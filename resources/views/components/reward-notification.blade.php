@props(['rewards' => null])

@if($rewards)
<div class="w-full flex justify-center fixed top-8 left-0 z-20">
    <div x-data="{ open: true, width: 0 }" x-show="open" x-init="let interval = setInterval(() => { if (width < 105) width += 5; else {clearInterval(interval); open = false;} }, 200)" class="bg-content ring-1 ring-content_text ring-opacity-25 text-content_text p-4 pb-5 shadow-lg absolute z-20 top-20">
        <div class="w-full bg-gray-200 absolute bottom-0 left-0 h-1">
            <div class="bg-accent h-1" :style="'width: ' + width + '%; transition: width 0.5s ease-out'"></div>
        </div>
        <div class="flex justify-between items-start relative pl-2 pt-3 pb-1 pr-3">
            <div class="flex flex-col space-y-1">
                <div class="text-xl">{{__('interfaces.rewards')}}</div>
                @foreach ($rewards as $reward)
                    <x-icon-with-text icon='{{$reward->icon}}'> {{$reward->quantity}} {{$reward->item}}</x-icon-with-text>
                @endforeach
            </div>
            <button class="absolute -right-2 -top-3 text-xl" @click="open = false">&times;</button>
        </div>
    </div>
</div>
@endif