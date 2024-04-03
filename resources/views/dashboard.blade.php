<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-color_text leading-tight">
            {{ __('titles.dashboard') }}
        </h2>
    </x-slot>

    <div class="text-color_text">
        {{-- {{ __("You're logged in") }}, {{$name}} ! --}}
        
        <div>
            <h3 class="text-content_text text-lg">{{__('titles.daily_reward')}}</h3>
            <x-daily-reward collectedDays={{$collectedDays}} collectedToday={{$collectedToday}} />
        </div>
    </div>
</x-app-layout>
