<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * Manages notification queue and handles display logic.
 */
class Notification extends Component
{
    public $notifications = [];

    protected $listeners = ['notify'];

    /**
     * Adds a new notification to the queue.
     *
     * @param string $type Notification type (success, error, info, warning)
     * @param string $message Notification message
     */
    public function notify($type, $message)
    {
        $this->notifications[] = [
            'id' => uniqid(),
            'type' => $type,
            'message' => $message,
        ];

        $this->dispatch('remove-notification');
    }

    /**
     * Removes the oldest notification from the queue.
     */
    public function removeOldestNotification()
    {
        array_shift($this->notifications);
    }

    public function render()
    {
        return view('livewire.notification');
    }
}
