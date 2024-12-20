<div x-data="{ notifications: @entangle('notifications') }" class="fixed bottom-5 right-5 space-y-3 z-50">
    <template x-for="notification in notifications" :key="notification.id">
        <div x-data="{ visible: true }" x-init="setTimeout(() => { visible = false }, 4500);
        setTimeout(() => { $wire.call('removeOldestNotification') }, 5000);" x-show="visible"
            x-transition:enter="transition ease-out duration-500 transform"
            x-transition:enter-start="translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition ease-in duration-500 transform"
            x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-2 opacity-0"
            class="px-4 py-3 rounded shadow-lg"
            :class="{
                'bg-green-500 text-white': notification.type === 'success',
                'bg-red-500 text-white': notification.type === 'error',
                'bg-blue-500 text-white': notification.type === 'info',
                'bg-yellow-500 text-white': notification.type === 'warning'
            }">
            <span x-text="notification.message"></span>
        </div>
    </template>
</div>
