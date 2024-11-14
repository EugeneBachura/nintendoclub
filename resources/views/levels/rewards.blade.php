<x-app-layout>
    <x-slot name="slim"></x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-color_text leading-tight">
                {{ __('titles.level_rewards') }}
            </h2>
            <p class="mt-2 text-xs opacity-50">
                {{ __('descriptions.level_rewards') }}
            </p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <div class="overflow-hidden sm:rounded-lg">
            <div class="text-color_text">
                @foreach ($levels as $level)
                    <div class="px-6 py-4">

                        <h4 class="text-lg font-semibold @if ($userLevel == $level->level) text-accent @endif">
                            {{ \Illuminate\Support\Str::ucfirst(__('profiles.level')) }}
                            {{ $level->level }} </h4>
                        @if ($level->level == 1)
                        @else
                            <p class="text-xs opacity-50">{{ $level->experience_required }}
                                {{ __('profiles.exp') }} </p>
                        @endif

                        <div class="mt-2">
                            @if ($level->level != 1)
                                <p class="text font-bold"> {{ __('profiles.rewards') }}: </p>
                            @endif
                            <ul class="list-disc pl-5 text-sm">
                                @if ($level->coins > 0)
                                    <li>{{ $level->coins }} {{ __('profiles.coins') }}</li>
                                @endif
                                @if ($level->premium_points > 0)
                                    <li>{{ $level->premium_points }} {{ __('profiles.premium_points') }}</li>
                                @endif
                                @if ($level->item_id)
                                    <li>Item: {{ $level->item->name ?? 'Unknown Item' }}</li>
                                @endif
                                @if ($level->badge_id)
                                    <li>Badge: {{ $level->badge->name ?? 'Unknown Badge' }}</li>
                                @endif
                            </ul>
                        </div>

                        @if ($level->getLocalizedDescription())
                            <p class="text-sm mt-2">{{ $level->getLocalizedDescription() }}</p>
                        @endif
                    </div>

                    @if (!$loop->last)
                        <hr class="opacity-50 mx-6">
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
