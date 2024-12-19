<div x-data="{ visible: false }" x-init="$watch('visible', value => {
    if (value) {
        setTimeout(() => visible = false, 5000);
    }
})" x-show="visible" x-transition.duration.500ms
    x-on:notify.window="visible = true" class="fixed top-5 right-5 z-50">
    @if ($message)
        <div
            class="px-4 py-3 rounded shadow-lg 
                    @if ($type == 'success') bg-green-500 text-white 
                    @elseif ($type == 'error') bg-red-500 text-white 
                    @elseif ($type == 'info') bg-blue-500 text-white 
                    @else bg-yellow-500 text-white @endif">
            {{ $message }}
        </div>
    @endif
</div>
