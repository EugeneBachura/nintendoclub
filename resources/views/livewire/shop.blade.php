<div>
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

    <div class="max-w-7xl mx-auto">
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
                                    {{ $item->getLocalizedData()['description'] }}
                                </p>
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
                                        <x-button-buy disabled=true text="{{ __('buttons.no_item') }}" />
                                    @else
                                        <button wire:click="buyItem({{ $item->id }})"
                                            class="inline-flex items-center px-4 py-2 bg-success border border-transparent rounded-md font-semibold text-xs text-success-text uppercase tracking-widest hover:bg-success-hover active:bg-success-900 focus:outline-none focus:border-success-900 focus:ring focus:ring-success-300 disabled:opacity-25 transition">
                                            {{ __('buttons.buy') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="flex justify-end sm:mx-6">
            <x-button-link class="bg-grey text-grey-text hover:bg-grey-hover mb-4 mt-4 sm:mb-0 sm:mt-4"
                href="{{ route('transactions.history') }}">{{ __('titles.history_shop') }}</x-button-link>
        </div>
    </div>
</div>
