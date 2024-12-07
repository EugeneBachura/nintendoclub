<x-app-layout>
    <x-slot name="slim"></x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-color_text leading-tight">
                {{ __('titles.level_rewards') }}
            </h2>
            <p class="text-xs opacity-50">
                {{ __('descriptions.level_rewards') }}
            </p>
        </div>
    </x-slot>
    <x-slot name="title">
        {{ __('titles.level_rewards') }}
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <div class="overflow-hidden sm:rounded-lg">
            <div class="text-color_text text-justify">
                <div class="px-6 py-4 flex flex-col items-center space-y-2">
                    <h4 class="flex">
                        {{ __('profiles.coins') }} <div class="ml-2 w-5 h-5 inline-block"><x-icon name="coins" />
                        </div>
                    </h4>
                    <p class="text-sm opacity-50">
                        {{ __('descriptions.rewards_coins') }}
                    </p>
                </div>
                <div class="px-6 py-4 flex flex-col items-center space-y-2">
                    <h4 class="flex">
                        {{ __('profiles.experience') }} <div class="ml-2 w-5 h-5 inline-block"><x-icon name="exp"
                                fill="#4687c3" />
                        </div>
                    </h4>
                    <p class="text-sm opacity-50">
                        {{ __('descriptions.rewards_experience') }}
                    </p>
                </div>
                <div class="px-6 py-4 flex flex-col items-center space-y-2">
                    <h4 class="flex">
                        {{ __('profiles.badges') }} <div class="ml-2 w-5 h-5 inline-block"><x-icon name="badge"
                                fill="#FFD700" />
                        </div>
                    </h4>
                    <p class="text-sm opacity-50">
                        {{ __('descriptions.rewards_badges') }}
                    </p>
                </div>
                <div class="px-6 py-4 flex flex-col items-center space-y-2">
                    <h4 class="flex">
                        {{ __('profiles.premium_points') }} <div class="ml-2 w-5 h-5 inline-block"><x-icon
                                name="premium_points" /> </div>
                    </h4>
                    <p class="text-sm opacity-50">
                        {{ __('descriptions.rewards_premium_points') }}
                    </p>
                </div>
                <hr class="opacity-50 mx-6 my-12">
                <div class="flex flex-col items-center">
                    @foreach ($levels as $level)
                        <div class="px-6 pb-4 flex flex-col items-center max-w-[520px]">
                            <h4
                                class="text-lg flex justify-center @if ($userLevel == $level->level) text-accent @endif">
                                {{ \Illuminate\Support\Str::ucfirst(__('profiles.level')) }}
                                {{ $level->level }} </h4>
                            @if ($level->level == 1)
                            @else
                                <p class="text-xs opacity-50 flex justify-center">
                                    {{ $level->experience_required }}
                                    {{ __('profiles.exp') }} </p>
                            @endif

                            <div class="flex mt-2">
                                @if ($level->level != 1)
                                    <div class="flex items-center border border-1 border-grey border-r-0 p-3">
                                        <ul
                                            class="list-none mt-1 text-center text-sm flex flex-col justify-center items-center">
                                            @if ($level->coins > 0)
                                                <li class="flex items-center">{{ $level->coins }}
                                                    <div class="w-3 h-3 inline-block ml-1"><x-icon name="coins" />
                                                    </div>
                                                </li>
                                            @endif
                                            @if ($level->premium_points > 0)
                                                <li>{{ $level->premium_points }} <div
                                                        class="w-3 h-3 inline-block ml-1">
                                                        <x-icon name="premium_points" />
                                                    </div>
                                                </li>
                                            @endif
                                            @if ($level->item_id)
                                                <li>Item: {{ $level->item->name ?? 'Unknown Item' }}</li>
                                            @endif
                                            @if ($level->badge_id)
                                                <li>Badge: {{ $level->badge->name ?? 'Unknown Badge' }}</li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif
                                @if ($level->getLocalizedDescription())
                                    <div
                                        class="flex items-center border border-1 border-grey p-3 text-sm text-center @if ($level->level != 1)  @endif">
                                        {{ $level->getLocalizedDescription() }}</div>
                                @endif
                            </div>

                            @if (!$loop->last)
                                <div class="text-2xl mx-6 pt-4">
                                    ↓
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
