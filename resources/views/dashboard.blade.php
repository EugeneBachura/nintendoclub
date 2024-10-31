<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-color_text leading-tight">
            {{ __('titles.dashboard') }}
        </h2>
    </x-slot>

    <div class="text-color_text">
        <div>
            <h3 class="text-content_text text-lg">{{ __('titles.daily_reward') }}</h3>
            <x-daily-reward collectedDays={{ $collectedDays }} collectedToday={{ $collectedToday }} />
        </div>
    </div>

    <div class="text-xs opacity-50 flex justify-end w-full pt-2 -mb-4">
        @if (app()->getLocale() == 'en')
            <a href="{{ route('updates') }}">Test Ver. 0.3.2</a>
        @else
            <a href="@localizedRoute('updates.locale')">Test Ver. 0.3.2</a>
        @endif
    </div>
</x-app-layout>
