<footer class="bg-nav text-gray-400 py-6 pt-8 mt-4 px-6">
    <div class="container mx-auto flex justify-between items-center">
        <div>
            <p class="text-lg font-semibold">
            <div class="shrink-0 flex items-center text-content_text">
                <a href="{{ localized_url('home') }}" class="flex content-center items-center">
                    <div class="text-sm flex items-center"><span
                            class="text-accent text-xl mb-[3px] font-semibold">N</span>intendo <span
                            class="text-accent text-xl mb-[3px] ml-0.5 font-semibold">F</span>an <span
                            class="text-accent text-xl mb-[3px] mx-0.5 font-semibold">.</span> Club</div>
                </a>
            </div>
            </p>
            <p class="text-xs text-content_text opacity-50 sm:text-sm max-w-[200px]">A place where Nintendo fans unite,
                we play with
                heart!</p>
        </div>
        <div class="space-y-2 opacity-50 sm:text-sm text-xs ml-5 min-w-[150px]">
            <div class="space-y-1 flex flex-col items-end">
                <div><a href="{{ route('levels.rewards') }}"
                        class="text-content_text hover:text-content_text-hover">{{ __('buttons.how_get_rewards') }}</a>
                </div>
                <div><a href="{{ route('terms') }}"
                        class="text-content_text hover:text-content_text-hover">{{ __('titles.legal_information') }}</a>
                </div>
                <div><a href="{{ route('contacts') }}"
                        class="text-content_text hover:text-content_text-hover">{{ __('titles.contact_administration') }}</a>
                </div>
                <div><a href="{{ route('profile.search') }}"
                        class="text-content_text hover:text-content_text-hover">{{ __('titles.profile_search') }}</a>
                </div>
            </div>
        </div>
    </div>
</footer>
<div class="bg-nav text-content_text py-2">
    <div class="container mx-auto text-center text-xs opacity-50">
        <p>&copy; 2025 NintendoFan.Club. All Rights Reserved.</p>
    </div>
</div>
