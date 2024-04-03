<x-app-layout>
    <x-slot name="slim"></x-slot>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-color_text leading-tight">
                {{ __('titles.profile_search') }}
            </h2>
        </div>
    </x-slot>

    <div class="text-color_text space-y-5">
        <form action="{{ route('profile.search') }}" method="GET" class="flex ">
            <input type="text" name="query" placeholder="{{__('profiles.search')}}" value="{{ request('query') }}" class="mb-2 p-2 w-full rounded text-content_text bg-background focus:outline-none">
            {{-- <x-button type="submit" class="bg-content-hover">{{__('profiles.search')}}</x-button> --}}
        </form>
    
        @if(isset($users))
            <div class="flex flex-col space-y-2">
                @forelse ($users as $user)
                    <div class="text-content_text hover:text-content_text-hover">
                        <a href="{{ route('profile.show', $user->id) }}">
                            {{ $user->nickname }} ({{ $user->name }})
                        </a>
                    </div>
                @empty
                    <div>{{__('messages.no_users_found')}}</div>
                @endforelse
            </div>
        @endif
    </div>
    <div class="mt-4 mx-2">
        {{ $users->links() }}
    </div>
</x-app-layout>