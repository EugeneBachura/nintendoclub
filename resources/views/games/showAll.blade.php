<x-app-layout>
    <x-slot name="slim"></x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-content_text leading-tight">
                {{ __('Games') }}
            </h2>
        </div>
        <div>
            <x-breadcrumb :breadcrumbs="$breadcrumbs" />
        </div>
    </x-slot>

    <style>
        .bg-content {
            padding: 0;
        }
    </style>

    <div class="max-w-7xl mx-auto">
        <div class="overflow-hidden sm:rounded-lg">
            <div>
                @foreach ($games as $game)
                    <a href="{{ route('game.show', ['alias' => $game->alias, 'lang' => app()->getLocale()]) }}"
                        class="text-lg font-semibold text-content_text hover:bg-content-table">
                        <div
                            class="flex items-center px-5 py-4 @if (!$loop->even) bg-content-table2 hover:bg-content-table @endif">
                            <!-- Логотип игры -->
                            <div class="flex-shrink-0 w-16 h-16 mr-4">
                                @if ($game->logo_url)
                                    <img src="{{ asset('storage/' . $game->logo_url) }}"
                                        alt="{{ $game->localizedName }}" width="64" height="64"
                                        class="rounded-md">
                                @else
                                    <div class="w-16 h-16 bg-gray-300 rounded-md"></div>
                                @endif
                            </div>

                            <!-- Информация об игре: название и дата выхода -->
                            <div class="flex-1">
                                <div class="text-lg font-semibold text-content_text">
                                    {{ $game->localizedName }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ date('Y', strtotime($game->release_date)) }}
                                </div>
                            </div>

                            <!-- Рейтинг -->
                            <div class="text-right">
                                <h3
                                    class="text-xl font-semibold rounded-full {{ $game->score_color }} text-successfully-text h-12 w-12 flex items-center justify-center ml-8">
                                    {{ number_format($game->average_score, 1) }}
                                </h3>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        {{-- Пагинация --}}
        <div class="mt-4 m-2">
            {{ $games->links() }}
        </div>
    </div>
</x-app-layout>
