<x-app-layout>
    <x-slot name="slim"></x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-color_text leading-tight">
                Add Post
            </h2>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('post.store') }}" enctype="multipart/form-data" id="postCreateForm">
        @csrf

        <div
            class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
            <x-input-text id="title" name="title" required label="Title" maxlength="255"
                value="{{ old('title') }}" description="{{ __('interfaces.maxchar_255') }}" />
        </div>

        <div @unlessrole('review_editor|administrator') hidden @endunlessrole
            class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
            @php
                $statusOptions = [];
                if (Auth::user()->hasRole('user')) {
                    $statusOptions = [
                        'under_review' => 'Under Review',
                    ];
                }
                if (Auth::user()->hasRole('administrator')) {
                    $statusOptions = [
                        'active' => 'Active',
                        'hidden' => 'Hidden',
                        'under_review' => 'Under Review',
                        'deleted' => 'Deleted',
                    ];
                }
            @endphp
            <x-select name="status" label="Status" :options="$statusOptions" description="Choose a status" required
                selected="{{ old('status') }}" />
        </div>

        <div
            class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
            @php
                $categoriesOptions = [];
                foreach ($categories as $category) {
                    $locale = app()->getLocale();
                    switch ($locale) {
                        case 'en':
                            $categoriesOptions[$category->id] = $category->name_en;
                            break;
                        case 'ru':
                            $categoriesOptions[$category->id] = $category->name_ru;
                            break;
                        case 'pl':
                            $categoriesOptions[$category->id] = $category->name_pl;
                            break;
                    }
                }
            @endphp
            <x-select name="category_id" label="Category" :options="$categoriesOptions" description="Choose a category" required
                selected="{{ old('status') }}" />
        </div>

        <div
            class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
            <x-input-file name="image" label="Image" description="Recomendet size: 800x400px. Accepted formats: .jpg"
                accept=".jpg" />
        </div>

        @role('administrator')
            <div
                class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
                <x-input-text id="keywords" name="keywords" value="{{ old('keywords') }}" label="Keywords" maxlength=255
                    description="{{ __('interfaces.maxchar_255') }}" />
            </div>

            <div
                class="pb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
                <x-input-text id="seo_description" name="seo_description" value="{{ old('seo_description') }}"
                    label="Description" maxlength="255" description="{{ __('interfaces.maxchar_255') }}" />
            </div>

            <div
                class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
                <x-input-text id="alias" name="alias" label="Alias" maxlength="255" value="{{ old('alias') }}"
                    description="{{ __('interfaces.maxchar_255') }}" />
            </div>

            <div
                class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
                <x-input-text id="language" name="language" required label="Language" maxlength="2"
                    value="{{ old('language') }}" description="en / pl / ru ..." />
            </div>
        @endrole

        <div class="pb-3 mb-3 space-y-1 relative">
            <x-editor name="content" label="Content" value="{{ old('content') }}" description="Write content" />
        </div>
        <x-editor-init />

        <div class="flex items-center justify-end mt-4">
            <x-button-save text="Submit" class="ml-4" form="postCreateForm" />
        </div>
    </form>
    </div>
</x-app-layout>
