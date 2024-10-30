@props(['collectedDays' => 1, 'collectedToday' => false])

<div class="mt-2">
    <div class="">
        <div class="flex flex-row items-center flex-wrap space-x-2 space-y-2">
            @for ($i = 0; $i < 7; $i++)
                <div class="inline-block mt-2">
                    @if ($collectedToday)
                        <div
                            class="{{ $collectedDays > $i ? 'bg-success' : 'border' }} {{ $collectedDays == $i ? 'border-dashed' : '' }} px-2 cursor-pointer rounded flex flex-col items-start">
                        @else
                            <div
                                class="{{ $collectedDays > $i ? 'bg-success' : 'border' }} {{ $collectedDays == $i ? 'border-dashed' : '' }} px-2 cursor-pointer rounded flex flex-col items-start">
                    @endif
                    <div class="flex">
                        @switch($i)
                            @case(0)
                                <x-icon-with-text class="min-w-12 flex justify-center" icon="coins"
                                    tooltip="{{ __('profiles.coins') }}">
                                    1
                                </x-icon-with-text>
                            @break

                            @case(1)
                                <x-icon-with-text class="min-w-12 flex justify-center" icon="coins"
                                    tooltip="{{ __('profiles.coins') }}">
                                    2
                                </x-icon-with-text>
                            @break

                            @case(2)
                                <x-icon-with-text class="min-w-12 flex justify-center" icon="coins"
                                    tooltip="{{ __('profiles.coins') }}">
                                    3
                                </x-icon-with-text>
                            @break

                            @case(3)
                                <x-icon-with-text class="min-w-12 flex justify-center" icon="coins"
                                    tooltip="{{ __('profiles.coins') }}">
                                    4
                                </x-icon-with-text>
                            @break

                            @case(4)
                                <x-icon-with-text class="min-w-12 flex justify-center" icon="coins"
                                    tooltip="{{ __('profiles.coins') }}">
                                    5
                                </x-icon-with-text>
                            @break

                            @case(5)
                                <x-icon-with-text class="min-w-12 flex justify-center" icon="coins"
                                    tooltip="{{ __('profiles.coins') }}">
                                    6
                                </x-icon-with-text>
                            @break

                            @case(6)
                                <x-icon-with-text class="min-w-12 flex justify-center" icon="premium_points"
                                    tooltip="{{ __('profiles.premium_points') }}">
                                    1
                                </x-icon-with-text>
                            @break

                            @default
                        @endswitch
                    </div>
                </div>
        </div>
        @if ($i != 6)
            <div class="text-xl">></div>
        @endif
        @endfor
    </div>
    <form action="{{ route('collect.daily.reward') }}" method="POST" class="mt-6 flex">
        @csrf
        <div class="flex flex-col">
            <button type="submit" {{ $collectedToday ? 'disabled' : '' }}
                {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-success border border-transparent rounded-md font-semibold text-xs text-success-text uppercase tracking-widest hover:bg-success-hover active:bg-success-900 focus:outline-none focus:border-success-900 focus:ring focus:ring-success-300 disabled:opacity-25 transition']) }}>
                @if (!$collectedToday)
                    {{ __('buttons.collect_daily') }}
                @else
                    {{ __('buttons.today_collected') }}
                @endif
            </button>
            @if ($collectedToday)
                <div class="mt-1 text-xs text-content_text opacity-50" x-data="rewardTimer('{{ now() }}')">
                    {{ __('interfaces.time_left') }}
                    <span x-text="timeLeft.hours"></span>:<span x-text="timeLeft.minutes"></span>:<span
                        x-text="timeLeft.seconds"></span>
                </div>
            @endif
        </div>
    </form>
</div>
</div>
<script>
    function rewardTimer(initialServerTime) {
        return {
            timeLeft: {
                hours: '00',
                minutes: '00',
                seconds: '00'
            },
            serverTime: new Date(initialServerTime),
            loadTime: new Date(), // Время загрузки страницы

            init() {
                this.updateTimeLeft();
                setInterval(() => {
                    this.updateTimeLeft();
                }, 1000);
            },

            updateTimeLeft() {
                // Вычисление текущего времени на сервере, исходя из времени загрузки
                const currentTime = new Date();
                const timeSinceLoad = currentTime - this.loadTime;
                let adjustedServerTime = new Date(this.serverTime.getTime() + timeSinceLoad);

                // Установка 'tomorrow' на следующий день от adjustedServerTime
                const tomorrow = new Date(adjustedServerTime);
                tomorrow.setDate(tomorrow.getDate() + 1);
                tomorrow.setHours(0, 0, 0, 0);

                // Вычисление разницы во времени
                const timeDiff = tomorrow - adjustedServerTime;

                if (timeDiff >= 0) {
                    this.timeLeft.hours = Math.floor(timeDiff / (1000 * 60 * 60)).toString().padStart(2, '0');
                    this.timeLeft.minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(
                        2, '0');
                    this.timeLeft.seconds = Math.floor((timeDiff % (1000 * 60)) / 1000).toString().padStart(2, '0');
                } else {
                    this.timeLeft = {
                        hours: '00',
                        minutes: '00',
                        seconds: '00'
                    };
                }
            }
        }
    }
</script>
