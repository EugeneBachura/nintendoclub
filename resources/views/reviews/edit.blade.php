<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-color_text leading-tight">
                Edit Reviews
            </h2>
        </div>
    </x-slot>

    <form method="POST" id="reviewEditForm" action="{{ route('review.updateForAdmin', ['gameId'=>$review->game_id, 'id'=>$review->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="pb-3 mb-3 space-y-1 relative">
            <p>{{$review->content}}</p>
            <p>Reting: {{$review->rating}}</p>
            <p>Exp: {{substr_count(preg_replace('/\s+/', ' ', trim($review->content)), ' ');}}</p>
        </div>

        <div class="pb-3 mb-3 space-y-1 relative">
            <x-input-text id="status_text" name="status_text" required label="Message for user" maxlength="255" value="{{ old('status_text', $review->status_text) }}" description="{{__('interfaces.maxchar_255')}}"/>
        </div>

        <div class="pb-3 mb-3 space-y-1 relative">
            @php
                $statusOptions = [];
                //$statusOptions[0] = 'pending';
                $statusOptions['published'] = 'published';
                $statusOptions['closed'] = 'closed';
            @endphp
            <x-select name="status"
            label="Status"
            :options="$statusOptions"
            description="Choose a status" required 
            selected="{{old('status', $review->status)}}"
            />
        </div>

        <div class="pb-3 mb-3 space-y-1 relative">
            <x-input-text id="money" name="money" label="Awards" maxlength="4" value="{{ old('money', null) }}"/>
        </div>

        <div class="pb-3 mb-3 space-y-1 relative after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-current after:opacity-10">
            @php
                $languagesOptions = [];
                $languagesOptions['en'] = 'en';
                $languagesOptions['pl'] = 'pl';
                $languagesOptions['ru'] = 'ru';
            @endphp
            <x-select name="language"
            label="Language"
            :options="$languagesOptions"
            description="Choose a language" required 
            selected="{{old('language', $review->language)}}"
            />
        </div>   

        <div class="flex items-center justify-end mt-4">
            <x-button-save text="Update" class="ml-4" form="reviewEditForm"/>
        </div>
    </form>
</div>
</x-app-layout>