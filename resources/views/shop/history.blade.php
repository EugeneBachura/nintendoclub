<x-app-layout>
    <x-slot name="slim"></x-slot>
    <x-slot name="header">
        <div class="flex justify-between">
            <div class="flex items-start content-center">
                <div class="py-2">
                    <h2 class="font-semibold text-xl text-color_text leading-tight">
                        {{ __('titles.history_shop') }}
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="">
                    <div class="flex flex-col py-6">
                        @foreach ($transactions as $transaction)
                            @php
                                $item = $transaction->item
                            @endphp
                            <div class="w-full relative p-4 pb-5 pt-3 flex flex-col justify-between border-b border-content_text border-opacity-25">
                                <div class="flex flex-col sm:flex-row justify-between">
                                    <div class="flex justify-between flex-1 flex-col sm:flex-row">
                                        <div class="flex items-center space-x-3 text-lg">
                                            @if ($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->getLocalizedData()['name'] }}" class="h-12 w-12 object-cover rounded">
                                            @endif
                                            <h3 class="text-lg font-bold">{{ $item->getLocalizedData()['name'] }} x {{$transaction->quantity}}</h3>
                                        </div>
                                        {{-- <p class="text-sm text-content_text mt-4">{{ $item->getLocalizedData()['description'] }}</p> --}}
                                        <div class="flex sm:justify-between items-center justify-end sm:mt-0 -mt-5">
                                            <span class="text-lg font-bold">
                                                <x-icon-with-text icon="{{ $transaction->currency }}" tooltip="{{__('profiles.coins')}}">
                                                    {{ floor($transaction->price) }} 
                                                </x-icon-with-text>
                                            </span>
                                            <div class="flex items-center flex-1 justify-end absolute bottom-2 right-3 opacity-50">
                                                <span class="text-xs font-normal justify-end">
                                                    {{$transaction->created_at}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>