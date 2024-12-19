<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * Displays user's avatar dynamically.
 */
class Avatar extends Component
{
    public $src;
    public $size;
    public $class;

    public function mount($src, $size = 'w-16 h-16', $class = '')
    {
        $this->src = $src;
        $this->size = $size;
        $this->class = $class;
    }

    public function render()
    {
        return view('livewire.avatar');
    }
}
