<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Textarea extends Component
{
    public $name;
    public $value;
    public $rows;

    public function __construct($name, $value = '', $rows = 3)
    {
        $this->name = $name;
        $this->value = $value;
        $this->rows = $rows;
    }

    public function render()
    {
        return view('components.textarea');
    }
}
