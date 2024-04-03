<x-app-layout>
    <x-slot name="header">
    </x-slot>
        {{-- @if (Auth::user())
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-button type="submit" class="bg-discord hover:bg-discord-hover text-light_text hover:text-light_text-hover" >
                    Выйти из системы
                </x-button>
            </form>
        @else
        <x-button-link href="{{ route('auth.discord') }}" class="bg-discord hover:bg-discord-hover text-light_text hover:text-light_text-hover">
            Войти через Discord
        </x-button-link>
        @endif --}}
</x-app-layout>
