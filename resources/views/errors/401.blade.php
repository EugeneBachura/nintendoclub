<x-app-layout>
    <x-slot name="slim"></x-slot>
    <x-slot name="header">{{__('errors.error')}} 401</x-slot>

    <div class="text-color_text space-y-5">
        {{__('errors.401')}}
    </div>
</x-app-layout>