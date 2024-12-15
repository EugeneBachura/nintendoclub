<?php

namespace App\Http\Livewire;

use Livewire\Component;

/**
 * Displays user's avatar dynamically.
 */
class Avatar extends Component
{
    public $src;
    public $size = 'w-16 h-16';

    public function mount($src, $size = 'w-16 h-16')
    {
        $this->src = $src;
        $this->size = $size;
    }

    public function render()
    {
        return view('livewire.avatar');
    }
}
