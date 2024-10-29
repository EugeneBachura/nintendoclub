<x-app-layout>
    {{-- <x-slot name="slim"></x-slot> --}}
    <x-slot name="header">
        <div class="flex justify-between">
            <div class="flex items-start content-center">
                <div class="py-2">
                    <h2 class="font-semibold text-xl text-color_text leading-tight">
                        {{ __('titles.shop') }}
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($items as $item)
                            <div class="border border-content_text rounded-lg p-4 shadow flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center space-x-3 text-lg">
                                        @if ($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}"
                                                alt="{{ $item->getLocalizedData()['name'] }}"
                                                class="h-12 w-12 object-cover rounded shadow-lg">
                                        @endif
                                        <h3 class="text-lg font-bold">{{ $item->getLocalizedData()['name'] }}</h3>
                                    </div>
                                    <p class="text-sm text-content_text mt-4">
                                        {{ $item->getLocalizedData()['description'] }}</p>
                                </div>
                                <div class="mt-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-bold">
                                            <x-icon-with-text icon="{{ $item->shopItem->currency }}"
                                                tooltip="{{ __('profiles.' . $item->shopItem->currency) }}">
                                                {{ floor($item->shopItem->price) }}
                                            </x-icon-with-text>
                                        </span>
                                        @if ($item->shopItem->stock == 0 && !is_null($item->shopItem->stock))
                                            <x-button-buy disabled=true form="buy-{{ $item->id }}"
                                                text="{{ __('buttons.no_item') }}" />
                                        @else
                                            <form action="{{ route('shop.buy', $item->id) }}" method="POST"
                                                id="buy-{{ $item->id }}">
                                                @csrf
                                                <x-button-buy form="buy-{{ $item->id }}" />
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end sm:mx-6 sm:px-6 lg:px-8">
            @if (app()->getLocale() == 'en')
                <x-button-link class="bg-grey text-grey-text hover:bg-grey-hover mb-4 mt-4 sm:mb-0 sm:mt-4"
                    href="{{ route('transactions.history') }}">{{ __('titles.history_shop') }}</x-button-link>
            @else
                <x-button-link class="bg-grey text-grey-text hover:bg-grey-hover mb-4 mt-4 sm:mb-0 sm:mt-4"
                    href="{{ localizedRoute('transactions.history') }}">{{ __('titles.history_shop') }}</x-button-link>
            @endif
        </div>
    </div>
</x-app-layout>
