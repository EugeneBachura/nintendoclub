<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LanguageSwitcher extends Component
{
    public $currentLocale;
    public $supportedLocales;

    public function __construct()
    {
        $this->currentLocale = app()->getLocale();
        $this->supportedLocales = ['en', 'ru', 'pl'];
    }

    public function render()
    {
        return view('components.language-switcher');
    }
}