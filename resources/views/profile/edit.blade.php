<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-color_text leading-tight">
                {{__('titles.profile_edit')}}
            </h2>
        </div>
    </x-slot>

    <div x-data="favoriteGames" x-init="init()">
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
                <x-textarea id="profile_description" class="block mt-1 -ml-1 w-full" rows="3" label="{{__('profiles.description')}}" description="{{__('interfaces.maxchar_175')}}" name="profile_description" maxlength="175" value="{{old('profile_description', Auth::user()->profile->profile_description)}}"></x-textarea>
            </div>

            <div class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
                <x-label for="favorite_games" :value="__('profiles.favorite_games')" />
                <div class="flex flex-wrap pt-1" x-ref="selectedGamesContainer">
                    {{-- <!-- Здесь будут отображаться выбранные игры --> --}}
                </div>
                <div @click="if (selectedGames.length < 5) openModal()" class="flex max-w-fit items-center justify-center bg-background rounded-md cursor-pointer transform transition-transform hover:scale-105 p-2" :class="{ 'opacity-50 cursor-not-allowed': selectedGames.length >= 5 }">
                    <span class="text-content_text text-sm">{{__('profiles.add_games')}}</span>
                </div>
            </div>            

            {{-- <!-- Модальное окно для выбора игры --> --}}
            <div x-show="open" class="absolute p-2 text-sm text-content_text bg-content rounded shadow-lg whitespace-nowrap z-10 overflow-y-auto max-h-64" @click.away="open = false">
                <input type="text" x-model="search" placeholder="{{__('profiles.search')}}" class="mb-2 p-2 w-full rounded text-content_text bg-background focus:outline-none">
                <ul>
                    <template x-for="game in filteredGames" :key="game.id">
                        <li @click="addGame(game)" class="hover:bg-content-hover focus:outline-none focus:bg-content-hover cursor-pointer p-1 rounded truncated-text320">
                            <span class="truncate" x-text="game.name"></span>
                        </li>
                    </template>
                </ul>
            </div>

            {{-- <div class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
            @if(isset($nickname_colors) && count($nickname_colors) > 0)
                <x-label for="nickname_color" :value="__('profiles.nickname_color')" />
                <select class="mt-2" name="nickname_color" id="nickname_color" style="background-color: {{ old('nickname_color', $user->designSetting->nickname_color ?? '#ffffff') }}">
                    @foreach($nickname_colors as $color)
                        <option class="text-dark_text stroke-white" value="{{ $color }}" {{ old('nickname_color', $user->designSetting->nickname_color ?? '#ffffff') == $color ? 'selected' : '' }} style="background-color: {{ $color }}">
                            {{ $color }}
                        </option>
                    @endforeach
                </select>
            @endif
            </div>

            <div class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
                @if(isset($nickname_colors) && count($nickname_colors) > 0)
                    <x-label for="nickname_color" :value="__('profiles.nickname_color')" />
                    <select class="block mt-1 p-2 w-full rounded-md shadow-sm text-sm border-0 focus:ring focus:ring-background-hover focus:ring-opacity-100" name="nickname_color" id="nickname_color" style="background-color: {{ old('nickname_color', $user->designSetting->nickname_color ?? '#ffffff') }}">
                        @foreach($nickname_colors as $color)
                            <option class="text-dark_text stroke-white" value="{{ $color }}" {{ old('nickname_color', $user->designSetting->nickname_color ?? '#ffffff') == $color ? 'selected' : '' }} style="background-color: {{ $color }}">
                                {{ $color }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div> --}}

            <div x-data="{ color: '{{ old('nickname_color', $design->nickname_color ?? '#ffffff') }}' }" class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
                @if(isset($nickname_colors) && count($nickname_colors) > 0)
                    <x-label for="nickname_color" :value="__('profiles.nickname_color')" />
                    <div class="flex items-center max-w-md">
                        <select 
                            x-model="color"
                            class="bg-opacity-50 block mt-1 p-2 w-full rounded-md shadow-sm text-sm text-content_text bg-background border-0 focus:ring focus:ring-background-hover focus:ring-opacity-100"
                            name="nickname_color" 
                            id="nickname_color"
                            :style="'color: ' + color"
                        >
                            @foreach($nickname_colors as $color)
                                <option class="text-content_text bg-opacity-100 bg-background" style="color: {{$color}}" value="{{ $color }}">
                                    {{ $color }}
                                </option>
                            @endforeach
                        </select>
                        <div class="h-6 w-6 min-h-6 min-w-6 ml-3 rounded-full" :style="'background-color: ' + color"></div>
                    </div>
                    <span class="text-xs text-content_text opacity-50">*{{__('profiles.nickname_color_description')}}</span>
                @endif
            </div>

            <div class="mb-4 space-y-1">
                <x-label for="discord" :value="__('profiles.change_names')" />
                <p class="text-sm text-content_text opacity-50">
                    {{__('profiles.change_names_text')}}
                </p>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button-save text="{{__('buttons.save')}}" class="ml-4" />
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('favoriteGames', @json($favoriteGames));
            Alpine.data('favoriteGames', () => ({
                games: [],
                selectedGames: [],
                open: false,
                showTooltip: null,
                search: '',
                async init() {
                    await this.loadGames();
                    if (Array.isArray(Alpine.store('favoriteGames'))) {
                        this.selectedGames = Alpine.store('favoriteGames');
                    }
                    this.updateSelectedGamesDOM();
                },
                async loadGames() {
                    const response = await fetch('/games/list');
                    if (response.ok) {
                        this.games = await response.json();
                    } else {
                        console.error('Error game list');
                    }
                },
                get availableGames() {
                    return this.games.filter(game => !this.selectedGames.map(selectedGame => selectedGame.id).includes(game.id));
                },
                get filteredGames() {
                    return this.availableGames.filter(game => game.name.toLowerCase().includes(this.search.toLowerCase())).slice(0, 5);
                },
                addGame(game) {
                    if (this.selectedGames.length < 5) {
                        this.selectedGames.push(game);
                        this.updateSelectedGamesDOM();
                        this.closeModal();
                    } else {
                        alert('You can only select up to 5 games.');
                    }
                },
                removeGame(game) {
                    const index = this.selectedGames.findIndex(selectedGame => selectedGame.id === game.id);
                    if (index > -1) {
                        this.selectedGames.splice(index, 1);
                        this.updateSelectedGamesDOM();
                    }
                },
                updateSelectedGamesDOM() {
                    const container = this.$refs.selectedGamesContainer;
                    container.innerHTML = '';
                    this.selectedGames.forEach(game => {
                        const div = document.createElement('div');
                        div.classList.add('relative', 'bg-content', 'rounded-md', 'flex', 'items-center', 'justify-between', 'text-sm', 'text-content_text', 'mb-2');

                        const span = document.createElement('span');
                        span.classList.add('truncate', 'truncated-text320', 'opacity-50');
                        span.textContent = game.name;

                        const button = document.createElement('button');
                        button.classList.add('ml-2', 'mr-4', 'bg-background', 'hover:bg-content_hover', 'hover:text-accent-hover', 'p-1', 'rounded-md', 'focus:outline-none');
                        button.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                        button.addEventListener('click', () => this.removeGame(game));

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'favorite_games[]';
                        input.value = game.id;

                        div.appendChild(span);
                        div.appendChild(button);
                        div.appendChild(input);

                        container.appendChild(div);
                    });
                },
                openModal() {
                    this.open = true;
                    this.search = '';
                },
                closeModal() {
                    this.open = false;
                }
            }));
        });
    </script>
</x-app-layout>
