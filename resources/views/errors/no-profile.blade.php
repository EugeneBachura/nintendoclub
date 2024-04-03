<x-app-layout>
    <x-slot name="slim"></x-slot>
    <x-slot name="header">{{__('errors.error')}}</x-slot>

    <div class="text-color_text space-y-5">
        {{__('errors.no_profile')}}
    </div>
</x-app-layout>