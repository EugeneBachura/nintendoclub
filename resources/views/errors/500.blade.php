<x-app-layout>
    <x-slot name="slim"></x-slot>
    <x-slot name="header">{{__('errors.error')}} 500</x-slot>

    <div class="text-color_text space-y-5">
        {{__('errors.500')}}
    </div>
</x-app-layout>
