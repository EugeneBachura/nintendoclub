<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-content_text leading-tight">
                {{ __('Games') }}
            </h2>
        </div>
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto">
            <div class="overflow-x-auto shadow-xl sm:rounded-lg">
                <div>
                    @foreach ($games as $game)
                        <div @if($loop->even) class="bg-content-table2" @endif>
                            <div class="px-5 py-4 whitespace-nowrap text-sm text-content_text">
                                        @if (app()->getLocale() == 'en')
                                            <a href="{{ route('game.show', ['alias' => $game->alias, 'locale' => null]) }}" class="text-content_text hover:text-accent-hover">{{ $game->name }}</a><br>
                                        @else
                                            <a href="{{route('game.show.locale', ['alias' => $game->alias, 'locale' => app()->getLocale()])}}" class="text-content_text hover:text-accent-hover">
                                                {{ $game->getTranslation('name', app()->getLocale()) }}
                                            </a><br>
                                        @endif
                            </div>
                        </div>
                        @endforeach
                </div>
                <div class="mt-4 m-2">
                    {{ $games->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
