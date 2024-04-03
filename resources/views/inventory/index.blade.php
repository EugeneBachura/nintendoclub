<x-app-layout>
    <x-slot name="slim"></x-slot>
    <x-slot name="header">
        <div class="flex justify-between">
            <div class="flex items-start content-center">
                <div class="py-2">
                    <h2 class="font-semibold text-xl text-color_text leading-tight">
                        {{ __('titles.inventory') }} {{$userNickname}}
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 pt-12">
                    <div class="flex space-x-3 flex-wrap">
                        @foreach ($userItems as $userItem)
                            @php
                                $item = $userItem->item;
                                $item_img = asset('storage/' . $item->image);
                                $item_name = $item->getLocalizedData()['name'];
                            @endphp
                            <x-user-item item_img='{{$item_img}}' name='{{$item_name}}' quantity='{{$userItem->quantity}}'></x-user-item>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>