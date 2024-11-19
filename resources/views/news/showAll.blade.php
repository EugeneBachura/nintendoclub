<x-app-layout>
    <x-slot name="slim"></x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center mb-2">
            <h2 class="font-semibold text-xl text-content_text leading-tight">
                {{ __('titles.all_news') }}
            </h2>
        </div>
        <div>
            <x-breadcrumb :breadcrumbs="$breadcrumbs" />
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <div class="overflow-x-hidden">
            <div class="flex flex-wrap -mx-2">
                @foreach ($newsList as $news)
                    <a href="{{ route('news.show', ['alias' => $news->alias, 'lang' => app()->getLocale()]) }}"
                        class="w-full px-2 mb-4">
                        <div class="flex bg-content-hover sm:rounded-lg shadow">
                            @if ($news->image)
                                <div class="flex flex-1 z-0 justify-center items-center sm:rounded-l-lg md:max-w-[300px] md:min-w-[300px] max-w-[0px] min-w-[0px] relative bg-cover bg-center"
                                    style="background-image: url('{{ asset('storage/' . $news->image) }}');">
                                    <div class="w-full h-full bg-black absolute top-0 left-0 opacity-30"></div>
                                </div>
                            @endif
                            <div class="p-4 space-y-4 flex-1">
                                <div class="flex flex-col justify-between">
                                    <div class="flex items-center">
                                        <h2 class="font-bold text-lg">
                                            {{ $news->getTranslation('title', app()->getLocale()) }}</h2>
                                    </div>
                                </div>
                                <p class="text-sm">{!! $news->trimmedContent !!}</p>
                                <div class="pl-3 md:pl-0 flex justify-end space-x-1">
                                    <div class="flex h-max align-middle items-center space-x-1 rounded-lg">
                                        <div class="h-5 w-5 mt-0.5">
                                            <x-icon name="burn" fill="{{ $news->popularityColor }}"></x-icon>
                                        </div>
                                    </div>
                                    <div class="flex h-max align-middle items-center space-x-1.5 rounded-lg">
                                        <div class="mt-0.5">
                                            {{ $news->popularity }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-4 mx-2">
                {{ $newsList->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
