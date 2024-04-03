<div x-data="{ open: false }" class="relative">
    <x-dropdown align="right" width="64">
        <x-slot name="trigger">
            <button class="flex items-center py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-transparent relative">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M8.35179 20.2418C9.19288 21.311 10.5142 22 12 22C13.4858 22 14.8071 21.311 15.6482 20.2418C13.2264 20.57 10.7736 20.57 8.35179 20.2418Z" fill="#eeeeee"></path> <path d="M18.7491 9V9.7041C18.7491 10.5491 18.9903 11.3752 19.4422 12.0782L20.5496 13.8012C21.5612 15.3749 20.789 17.5139 19.0296 18.0116C14.4273 19.3134 9.57274 19.3134 4.97036 18.0116C3.21105 17.5139 2.43882 15.3749 3.45036 13.8012L4.5578 12.0782C5.00972 11.3752 5.25087 10.5491 5.25087 9.7041V9C5.25087 5.13401 8.27256 2 12 2C15.7274 2 18.7491 5.13401 18.7491 9Z" fill="#eeeeee"></path> </g></svg>
                @if (auth()->user()->unreadNotifications()->count() > 0)
                    <span id="notificationCount" class="w-4 h-4 flex items-center justify-center bg-accent text-nav_text text-xs rounded-full absolute top-px -right-1.5">{{ auth()->user()->unreadNotifications()->count() }}</span>
                @endif
            </button>
        </x-slot>

        <x-slot name="content">
            {{-- Dropdown --}}
                <ul>
                    @forelse (auth()->user()->unreadNotifications as $notification)
                        <li>
                            <div class="flex  hover:bg-content-hover focus:outline-none focus:bg-content-hover transition  duration-150 ease-in-out">
                                <a href="{{ url($notification->data['url'] ?? '#') }}" class="block flex-1 w-full pl-4 py-2 text-left text-base leading-5 text-content_text">
                                    {{ $notification->data['message'] }}
                                </a>
                                <button onclick="markNotificationAsRead('{{ $notification->id }}', this); return false;" class="flex justify-center items-center w-6 h-6 mr-1.5 mt-1.5 bg-background hover:bg-content_hover hover:text-accent-hover p-1 rounded-md focus:outline-none"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            </div>
                        </li>
                    @empty
                        <li class="text-center px-4 py-2 text-content_text">No notifications</li>
                    @endforelse
                </ul>
        </x-slot>
    </x-dropdown>
</div>
<div hidden class="w-64"></div>
<script>
    document.querySelector('[x-data]').__x.$data.notificationCount == {{ auth()->user()->unreadNotifications()->count() }};
    function markNotificationAsRead(notificationId, element) {
    fetch('/notifications/read/' + notificationId, {
        method: 'GET',
        headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
        throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        element.closest('li').remove();
        var notificationCounter = document.getElementById('notificationCount');
        var count = parseInt(notificationCounter.textContent) - 1;
        if (count > 0) {
            notificationCounter.textContent = count.toString();
        } else {
            notificationCounter.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('There has been a problem with your fetch operation:', error);
    });
    }
</script>