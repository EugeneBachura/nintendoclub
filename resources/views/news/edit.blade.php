<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-color_text leading-tight">
                Edit News
            </h2>
        </div>
    </x-slot>

    <form method="POST" id="newsEditForm" action="{{ route('news.update', $news->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div @role('editor') hidden @endrole
            class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
            @php
                $statusOptions = [];
                if (Auth::user()->hasRole('editor')) {
                    $statusOptions = [
                        'under_review' => 'Under Review',
                    ];
                } elseif (Auth::user()->hasRole('review_editor') || Auth::user()->hasRole('administrator')) {
                    $statusOptions = [
                        'active' => 'Active',
                        'hidden' => 'Hidden',
                        'under_review' => 'Under Review',
                        'deleted' => 'Deleted',
                    ];
                }
            @endphp
            <x-select name="status" label="Status" :options="$statusOptions" description="Choose a status" required
                selected="{{ old('status', $news->status) }}" />
        </div>

        <div
            class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
            @if ($news->image)
                <div class="pb-3 mb-3">
                    <img src="{{ asset('storage/' . $news->image) }}" alt="News Image" />
                </div>
            @endif
            <x-input-file name="image" label="Image" description="Recomendet size: 800x400px. Accepted formats: .jpg"
                accept=".jpg" />
        </div>

        <div class="pb-3 mb-3 space-y-1 relative">
            <x-input-text id="alias" name="alias" label="Alias" maxlength="255"
                value="{{ old('alias', $news->alias) }}" description="{{ __('interfaces.maxchar_255') }}" />
        </div>

        <div class="pb-3 mb-3 space-y-1 relative">
            <x-input-text id="video" name="video" label="Video" maxlength="255"
                value="{{ old('video', $news->video) }}" />
        </div>

        <div x-data="{ openTab: 'en' }">
            <div class="">
                <ul class="flex -mb-px" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
                    <li class="mr-2" role="presentation">
                        <button @click="openTab = 'en'" :class="{ 'text-accent': openTab === 'en' }"
                            class="inline-block p-4 text-sm font-medium text-center border-b-2 border-transparent"
                            role="tab" type="button">
                            English
                        </button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button @click="openTab = 'ru'" :class="{ 'text-accent': openTab === 'ru' }"
                            class="inline-block p-4 text-sm font-medium text-center border-b-2 border-transparent"
                            role="tab" type="button">
                            Русский
                        </button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button @click="openTab = 'pl'" :class="{ 'text-accent': openTab === 'pl' }"
                            class="inline-block p-4 text-sm font-medium text-center border-b-2 border-transparent"
                            role="tab" type="button">
                            Polski
                        </button>
                    </li>
                </ul>
            </div>
            <div id="myTabContent">
                <div x-show="openTab === 'en'" class="p-4 bg-transparent border border-content-border rounded-lg"
                    role="tabpanel">
                    <div
                        class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
                        <x-input-text id="en_title" name="en_title" label="Title"
                            value="{{ old('en_title', $news->getTranslation('title', 'en')) }}"
                            description="{{ __('interfaces.maxchar_255') }}" />
                    </div>
                    <div
                        class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
                        <x-editor name="en_content" label="Content"
                            value="{{ old('en_content', $news->getTranslation('content', 'en')) }}"
                            description="Write content" />
                    </div>
                    <div class="pb-3 space-y-1 relative">
                        <x-input-text id="en_keywords" name="en_keywords"
                            value="{{ old('en_keywords', $news->getTranslation('keywords', 'en')) }}" label="Keywords"
                            maxlength="255" description="{{ __('interfaces.maxchar_255') }}" />
                    </div>
                    <div class="pb-3 space-y-1 relative">
                        <x-input-text id="en_seo_description" name="en_seo_description"
                            value="{{ old('en_seo_description', $news->getTranslation('seo_description', 'en')) }}"
                            label="Description" maxlength="255" description="{{ __('interfaces.maxchar_255') }}" />
                    </div>
                </div>
                <div x-show="openTab === 'ru'" class="p-4 bg-transparent border border-content-border rounded-lg"
                    role="tabpanel">
                    <div
                        class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
                        <x-input-text id="ru_title" name="ru_title" label="Title"
                            value="{{ old('ru_title', $news->getTranslation('title', 'ru')) }}"
                            description="{{ __('interfaces.maxchar_255') }}" />
                    </div>
                    <div
                        class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
                        <x-editor name="ru_content" label="Content"
                            value="{{ old('ru_content', $news->getTranslation('content', 'ru')) }}"
                            description="Write content" />
                    </div>
                    <div class="pb-3 space-y-1 relative">
                        <x-input-text id="ru_keywords" name="ru_keywords"
                            value="{{ old('ru_keywords', $news->getTranslation('keywords', 'ru')) }}"
                            label="Keywords" maxlength="255" description="{{ __('interfaces.maxchar_255') }}" />
                    </div>
                    <div class="pb-3 space-y-1 relative">
                        <x-input-text id="ru_seo_description" name="ru_seo_description"
                            value="{{ old('ru_seo_description', $news->getTranslation('seo_description', 'ru')) }}"
                            label="Description" maxlength="255" description="{{ __('interfaces.maxchar_255') }}" />
                    </div>
                </div>
                <div x-show="openTab === 'pl'" class="p-4 bg-transparent border border-content-border rounded-lg"
                    role="tabpanel">
                    <div
                        class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
                        <x-input-text id="pl_title" name="pl_title" label="Title"
                            value="{{ old('pl_title', $news->getTranslation('title', 'pl')) }}"
                            description="{{ __('interfaces.maxchar_255') }}" />
                    </div>
                    <div
                        class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
                        <x-editor name="pl_content" label="Content"
                            value="{{ old('pl_content', $news->getTranslation('content', 'pl')) }}"
                            description="Write content" />
                    </div>
                    <div class="pb-3 space-y-1 relative">
                        <x-input-text id="pl_keywords" name="pl_keywords"
                            value="{{ old('pl_keywords', $news->getTranslation('keywords', 'pl')) }}"
                            label="Keywords" maxlength="255" description="{{ __('interfaces.maxchar_255') }}" />
                    </div>
                    <div class="pb-3 space-y-1 relative">
                        <x-input-text id="pl_seo_description" name="pl_seo_description"
                            value="{{ old('pl_seo_description', $news->getTranslation('seo_description', 'pl')) }}"
                            label="Description" maxlength="255" description="{{ __('interfaces.maxchar_255') }}" />
                    </div>
                </div>
            </div>
        </div>
        <x-editor-init />

        <div class="flex items-center justify-end mt-4">
            <x-button-save text="Update" class="ml-4" form="newsEditForm" />
        </div>
    </form>
    </div>
</x-app-layout>
