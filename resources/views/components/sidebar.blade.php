<div 
        class="text-dark_text transition-all duration-300 w-[220px] relative"
    > 
    <div x-show="open" class="px-4 py-8 space-y-2">
        <x-sidebar-link href="{{ route('dashboard') }}" icon="news">
            News
        </x-sidebar-link>
        <x-sidebar-link href="{{ route('dashboard') }}" icon="games">
            Games
        </x-sidebar-link>
        <x-sidebar-link href="{{ route('dashboard') }}" icon="guides">
            Guides
        </x-sidebar-link>
    </div>
</div>