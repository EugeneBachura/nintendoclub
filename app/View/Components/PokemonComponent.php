<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PokemonComponent extends Component
{
    public $pokemons;
    /**
     * Create a new component instance.
     */
    public function __construct($pokemons)
    {
        $this->pokemons = $pokemons;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.pokemon-component');
    }
}
