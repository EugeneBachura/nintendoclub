<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-content_text leading-tight">
                {{ __('News List') }}
            </h2>
            <x-button-link href="{{ route('game.create') }}">
                Add Game
            </x-button-link>
        </div>
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto">
            <div class="overflow-x-auto shadow-xl sm:rounded-lg">
                <table class="w-full divide-y divide-content-border">
                    <thead class="bg-content-table2">
                        <tr>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-content_text uppercase tracking-wider">
                                ID
                            </th>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-content_text uppercase tracking-wider">
                                Titles
                            </th>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-content_text uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-content-table divide-y divide-content-border">
                        @foreach ($games as $game)
                        <tr @if($loop->even) class="bg-content-table2" @endif>
                            <td class="px-5 py-4 whitespace-nowrap text-sm font-medium text-content_text">
                                {{ $game->id }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-content_text">
                                @foreach(['en', 'ru', 'pl'] as $locale)
                                        @if ($locale == 'en')
                                            <a href="{{ route('game.show', ['alias' => $game->alias, 'locale' => null]) }}" class="text-indigo-600 hover:text-indigo-900">({{ strtoupper($locale) }}) {{ $game->name }}</a><br>
                                        @else
                                            <a href="{{route('game.show.locale', ['alias' => $game->alias, 'locale' => $locale])}}" class="text-indigo-600 hover:text-indigo-900">
                                                ({{ strtoupper($locale) }}) {{ $game->getTranslation('name', $locale) }}
                                            </a><br>
                                        @endif
                                @endforeach
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right text-sm font-medium">
                                {{-- <a href="{{ route('news.edit', $news->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form action="{{ route('news.destroy', $news->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-accent hover:text-accent-hover">Delete</button>
                                </form> --}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4 m-2">
                    {{ $games->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
