<?php

namespace App\Models;

use Illuminate\Notifications\Notification;

class UserNotification extends Notification
{
    protected $message;
    protected $url;
    protected $locale;

    public function __construct($message, $url, $locale = null)
    {
        $this->message = $message;
        $this->url = $url;
        $this->locale = $locale ?? app()->getLocale();
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'url' => $this->url,
        ];
    }

    public function locale($locale)
    {
        $this->locale = $locale;
        return $this;
    }
}
