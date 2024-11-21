<x-app-layout>
    <x-slot name="slim"></x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-color_text leading-tight">
            {{ __('titles.dashboard') }}
        </h2>
    </x-slot>

    <div class="text-color_text">
        <div>
            <h3 class="text-content_text text-lg">{{ __('titles.daily_reward') }}</h3>
            <x-daily-reward :collectedDays="$collectedDays" :collectedToday="$collectedToday" />
        </div>
    </div>

    {{-- Ссылка на страницу с наградами --}}
    <div class="text-xs opacity-50 flex justify-end w-full pt-2 -mb-4">
        <a href="{{ route('updates') }}">Test Ver. 0.3.11</a>
    </div>
</x-app-layout>
