<x-app-layout>
    <x-slot name="slim"></x-slot>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-color_text leading-tight px-4">
                Contacts
            </h2>
        </div>
    </x-slot>
    <x-slot name="title">
        Contacts
    </x-slot>

    <div class="text-color_text space-y-5 mt-4">
        <div class="container mx-auto px-4">
            <section class="mb-6 flex space-x-2">
                <h2 class="text-base font-semibold">Discord:</h2>
                <a class="text-content_text hover:text-content_text-hover" href="https://discord.gg/nintendo-ru-community-691192063731040256">Nintendo Fan Club</a>
            </section>
            
            <section class="mb-6 flex space-x-2">
                <h2 class="text-base font-semibold">Email:</h2>
                <a class="text-content_text hover:text-content_text-hover" href="mailto:contact@nintendofan.club">contact@nintendofan.club</a>
            </section>
        </div>
    </div>
</x-app-layout>