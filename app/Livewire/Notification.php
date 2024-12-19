<?php

namespace App\Livewire;

use Livewire\Component;

class Notification extends Component
{
    public $message;
    public $type; // success, error, info, warning

    protected $listeners = ['notify'];

    public function notify($type, $message)
    {
        $this->type = $type;
        $this->message = $message;

        logger('Notification type: ' . $this->type . ', message: ' . $this->message);

        $this->dispatch('hide-notification');
    }

    public function render()
    {
        return view('livewire.notification');
    }
}
