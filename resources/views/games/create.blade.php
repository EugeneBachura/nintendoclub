<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-color_text leading-tight">
                Add Game
            </h2>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('game.store') }}" enctype="multipart/form-data" id="gameCreateForm">
        @csrf

        <div class="pb-3 mb-3 space-y-1 relative">
            <x-input-text id="name" name="name" required label="Name" maxlength="255" value="{{ old('name') }}" description="{{__('interfaces.maxchar_255')}}"/>
        </div>
        <div class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
            <x-input-file name="cover_image_url"
              label="Image"
              description="Recomendet size: 800x400px. Accepted formats: .jpg"
              accept=".jpg" />
        </div>
        <div class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
            <x-input-file name="logo_url"
              label="Logo"
              description="Recomendet size: 64x64px. Accepted formats: .jpg"
              accept=".jpg" />
        </div>

        <div class="pb-3 mb-3 space-y-1 relative">
            <x-input-text id="alias" name="alias" required label="Alias" maxlength="255" value="{{ old('alias') }}" description="{{__('interfaces.maxchar_255')}}"/>
        </div>

        <div class="pb-3 mb-3 space-y-1 relative">
            <x-input-text id="platform" name="platform" required label="Platform" maxlength="255" value="{{ old('platform') }}" description="{{__('interfaces.maxchar_255')}}"/>
        </div>

        <div class="pb-3 mb-3 space-y-1 relative">
            <x-input-text id="developer" name="developer" required label="Developer" maxlength="255" value="{{ old('developer') }}" description="{{__('interfaces.maxchar_255')}}"/>
        </div>

        <div class="pb-3 mb-3 space-y-1 relative">
            <x-input-text id="publisher" name="publisher" required label="Publisher" maxlength="255" value="{{ old('publisher') }}" description="{{__('interfaces.maxchar_255')}}"/>
        </div>

        <div class="pb-3 mb-3 space-y-1 relative">
            <x-input-text id="release_date" name="release_date" required label="Release Date" maxlength="255" value="{{ old('release_date') }}" description="{{__('interfaces.date_format')}}"/>
        </div>

        <div class="pb-3 mb-3 space-y-1 relative">
            <x-input-text id="video" name="video" required label="Video" maxlength="255" value="{{ old('video') }}" description="{{__('interfaces.maxchar_255')}}"/>
        </div>

        <div x-data="{ openTab: 'en' }">
            <div class="">
                <ul class="flex -mb-px" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
                    <li class="mr-2" role="presentation">
                        <button
                            @click="openTab = 'en'"
                            :class="{ 'text-accent': openTab === 'en' }"
                            class="inline-block p-4 text-sm font-medium text-center border-b-2 border-transparent"
                            role="tab"
                            type="button">
                            English
                        </button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button
                            @click="openTab = 'ru'"
                            :class="{ 'text-accent': openTab === 'ru' }"
                            class="inline-block p-4 text-sm font-medium text-center border-b-2 border-transparent"
                            role="tab"
                            type="button">
                            Русский
                        </button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button
                            @click="openTab = 'pl'"
                            :class="{ 'text-accent': openTab === 'pl' }"
                            class="inline-block p-4 text-sm font-medium text-center border-b-2 border-transparent"
                            role="tab"
                            type="button">
                            Polski
                        </button>
                    </li>
                </ul>
            </div>
            <div id="myTabContent">
                <div x-show="openTab === 'en'" class="p-4 bg-transparent border border-content-border rounded-lg" role="tabpanel">
                    <div class="pb-3 space-y-1 relative">
                        <x-input-text id="description" name="description" value="{{ old('description') }}" label="Description"/>
                    </div>
                    <div class="pb-3 space-y-1 relative">
                        <x-input-text id="seo_description" name="seo_description" value="{{ old('seo_description') }}" label="SEO Description" maxlength="255" description="{{__('interfaces.maxchar_255')}}"/>
                    </div>
                    <div class="pb-3 space-y-1 relative">
                        <x-input-text id="seo_keywords" name="seo_keywords" value="{{ old('seo_keywords') }}" label="Keywords" maxlength="255" description="{{__('interfaces.maxchar_255')}}"/>
                    </div>
                </div>
                <div x-show="openTab === 'ru'" class="p-4 bg-transparent border border-content-border rounded-lg" role="tabpanel">
                    <div class="pb-3 space-y-1 relative">
                        <x-input-text id="ru_description" name="ru_description" value="{{ old('ru_description') }}" label="Description"/>
                    </div>
                    <div class="pb-3 space-y-1 relative">
                        <x-input-text id="ru_seo_description" name="ru_seo_description" value="{{ old('ru_seo_description') }}" label="SEO Description" maxlength="255" description="{{__('interfaces.maxchar_255')}}"/>
                    </div>
                    <div class="pb-3 space-y-1 relative">
                        <x-input-text id="ru_seo_keywords" name="ru_seo_keywords" value="{{ old('ru_seo_keywords') }}" label="Keywords" maxlength="255" description="{{__('interfaces.maxchar_255')}}"/>
                    </div>
                </div>
                <div x-show="openTab === 'pl'" class="p-4 bg-transparent border border-content-border rounded-lg" role="tabpanel">
                    <div class="pb-3 space-y-1 relative">
                        <x-input-text id="pl_description" name="pl_description" value="{{ old('pl_description') }}" label="Description"/>
                    </div>
                    <div class="pb-3 space-y-1 relative">
                        <x-input-text id="pl_seo_description" name="pl_seo_description" value="{{ old('pl_seo_description') }}" label="SEO Description" maxlength="255" description="{{__('interfaces.maxchar_255')}}"/>
                    </div>
                    <div class="pb-3 space-y-1 relative">
                        <x-input-text id="pl_seo_keywords" name="pl_seo_keywords" value="{{ old('pl_seo_keywords') }}" label="Keywords" maxlength="255" description="{{__('interfaces.maxchar_255')}}"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-button-save text="Submit" class="ml-4" form="gameCreateForm" />
        </div>
    </form>
</div>
</x-app-layout>