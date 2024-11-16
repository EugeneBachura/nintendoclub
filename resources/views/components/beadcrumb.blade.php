@props(['breadcrumbs' => []])

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        {{-- Ссылка на главную страницу --}}
        <li class="breadcrumb-item list-none"><a href="{{ url('/') }}">Main</a></li>

        {{-- Остальные элементы хлебных крошек --}}
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($loop->last)
                {{-- Активный элемент (текущая страница) --}}
                <li class="breadcrumb-item active list-none" aria-current="page">{{ $breadcrumb['title'] }}</li>
            @else
                {{-- Ссылки на предыдущие страницы --}}
                <li class="breadcrumb-item list-none"><a
                        href="{{ url($breadcrumb['url']) }}">{{ $breadcrumb['title'] }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>
