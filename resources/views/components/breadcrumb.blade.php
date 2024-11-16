@props(['breadcrumbs' => []])

<nav aria-label="breadcrumb">
    <ol class="breadcrumb flex space-x-1 items-center text-sm">
        {{-- Ссылка на главную страницу --}}
        <li class="breadcrumb-item list-none"><a href="{{ localized_url('home') }}">{{ __('titles.main') }}</a></li>

        {{-- Остальные элементы хлебных крошек --}}
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($loop->last)
                {{-- Активный элемент (текущая страница) --}}
                <div class="h-5 flex"><x-icon name="arrow"></x-icon></div>
                <li class="breadcrumb-item active list-none" aria-current="page">{{ $breadcrumb['title'] }}</li>
            @else
                {{-- Ссылки на предыдущие страницы --}}
                <div class="h-5 flex"><x-icon name="arrow"></x-icon></div>
                <li class="breadcrumb-item list-none"><a
                        href="{{ url($breadcrumb['url']) }}">{{ $breadcrumb['title'] }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>
