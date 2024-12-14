@props(['breadcrumbs' => []])

<nav aria-label="breadcrumb">
    <ol class="breadcrumb flex space-x-1 items-center text-sm">
        <li class="breadcrumb-item list-none"><a href="{{ localized_url('home') }}">{{ __('titles.main') }}</a></li>

        @foreach ($breadcrumbs as $breadcrumb)
            @if ($loop->last)
                <div class="h-5 flex"><x-icon name="arrow"></x-icon></div>
                <li class="breadcrumb-item active list-none" aria-current="page">{{ $breadcrumb['title'] }}</li>
            @else
                <div class="h-5 flex"><x-icon name="arrow"></x-icon></div>
                <li class="breadcrumb-item list-none"><a
                        href="{{ url($breadcrumb['url']) }}">{{ $breadcrumb['title'] }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>
