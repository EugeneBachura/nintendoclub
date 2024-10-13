<x-app-layout>
    <x-slot name="slim"></x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-color_text leading-tight">
                Edit Post
            </h2>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('post.update', ['id' => $post->id]) }}" enctype="multipart/form-data"
        id="postEditForm"> @csrf
        @csrf
        @method('PUT')

        <div
            class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
            <x-input-text id="title" name="title" required label="Title" maxlength="255"
                value="{{ old('title', $post->title) }}" description="{{ __('interfaces.maxchar_255') }}" />
        </div>

        <div @unlessrole('review_editor|administrator') hidden @endunlessrole
            class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
            @php
                $statusOptions = [
                    'active' => 'Active',
                    'hidden' => 'Hidden',
                    'under_review' => 'Under Review',
                    'deleted' => 'Deleted',
                ];
            @endphp
            <x-select name="status" label="Status" :options="$statusOptions" description="Choose a status" required
                selected="{{ old('status', $post->status) }}" />
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
                selected="{{ old('status', $post->category_id) }}" />
        </div>

        <div
            class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
            <x-input-file name="image" label="Image"
                description="Recommended size: 800x400px. Accepted formats: .jpg" accept=".jpg"
                value="{{ old('image', $post->image) }}" />
            @if ($post->image)
                <img src="{{ asset('storage/posts_images/' . $post->image) }}" alt="Current Image"
                    style="max-width: 200px;">
            @endif
        </div>

        @role('review_editor|administrator')
            <div
                class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
                <x-input-text id="keywords" name="keywords" label="Keywords" maxlength=255
                    description="{{ __('interfaces.maxchar_255') }}" value="{{ old('keywords', $post->keywords) }}" />
            </div>

            <div
                class="pb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
                <x-input-text id="seo_description" name="seo_description" label="Description" maxlength="255"
                    description="{{ __('interfaces.maxchar_255') }}"
                    value="{{ old('seo_description', $post->seo_description) }}" />
            </div>

            <div
                class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
                <x-input-text id="alias" name="alias" label="Alias" maxlength="255"
                    value="{{ old('alias', $post->alias) }}" description="{{ __('interfaces.maxchar_255') }}" />
            </div>

            <div
                class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
                <x-input-text id="language" name="language" required label="Language" maxlength="2"
                    value="{{ old('language', $post->language) }}" description="en / pl / ru ..." />
            </div>
        @endrole

        <div class="pb-3 mb-3 space-y-1 relative">
            <x-editor name="content" label="Content" value="{{ old('content', $post->content) }}"
                description="Write content" />
        </div>
        <x-editor-init />

        <div class="flex items-center justify-end mt-4">
            <x-button-save text="Update" class="ml-4" form="postEditForm" />
        </div>
    </form>
</x-app-layout>
