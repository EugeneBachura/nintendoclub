<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NotificationsDropdown extends Component
{
    public $notifications;

    public function __construct()
    {
        $this->notifications = auth()->user()->notifications;
        // $notificationCount = auth()->user()->unreadNotifications->count();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.notifications-dropdown');
    }
}
