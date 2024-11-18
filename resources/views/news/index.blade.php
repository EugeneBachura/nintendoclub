<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-content_text leading-tight">
                {{ __('News List') }}
            </h2>
            <x-button-link href="{{ route('news.create') }}">
                Add News
            </x-button-link>
        </div>
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto">
            <div class="overflow-x-auto shadow-xl sm:rounded-lg">
                <table class="w-full divide-y divide-content-border">
                    <thead class="bg-content-table2">
                        <tr>
                            <th scope="col"
                                class="px-5 py-3 text-left text-xs font-medium text-content_text uppercase tracking-wider">
                                ID
                            </th>
                            <th scope="col"
                                class="px-5 py-3 text-left text-xs font-medium text-content_text uppercase tracking-wider">
                                Titles
                            </th>
                            <th scope="col"
                                class="px-5 py-3 text-left text-xs font-medium text-content_text uppercase tracking-wider">
                                Created
                            </th>
                            <th scope="col"
                                class="px-5 py-3 text-left text-xs font-medium text-content_text uppercase tracking-wider">
                                Edited
                            </th>
                            <th scope="col"
                                class="px-5 py-3 text-left text-xs font-medium text-content_text uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-content-table divide-y divide-content-border">
                        @foreach ($newsList as $news)
                            <tr @if ($loop->even) class="bg-content-table2" @endif>
                                <td class="px-5 py-4 whitespace-nowrap text-sm font-medium text-content_text">
                                    {{ $news->id }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-sm text-content_text">
                                    @foreach (config('localization.supported_locales') as $locale)
                                        @if ($title = $news->getTranslation('title', $locale))
                                            <a href="{{ route('news.show', ['alias' => $news->alias, 'lang' => $locale]) }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                ({{ strtoupper($locale) }})
                                                {{ $title }}
                                            </a><br>
                                        @endif
                                    @endforeach
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-sm text-content_text">
                                    <div>{{ $news->author->name ?? 'N/A' }}</div>
                                    <div>{{ $news->created_at->format('Y-m-d') }}</div>
                                    <div>{{ $news->created_at->format('H:i:s') }}</div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-sm text-content_text">
                                    <div>{{ $news->reviewer->name ?? 'N/A' }}</div>
                                    <div>{{ $news->updated_at->format('Y-m-d') }}</div>
                                    <div>{{ $news->updated_at->format('H:i:s') }}</div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('news.edit', $news->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    @unlessrole('editor')
                                        <form action="{{ route('news.destroy', $news->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-accent hover:text-accent-hover">Delete</button>
                                        </form>
                                    @endunlessrole
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4 m-2">
                    {{ $newsList->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
