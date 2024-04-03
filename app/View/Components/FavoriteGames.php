<?php

namespace App\View\Components;

use App\Models\Profile;
use Illuminate\View\Component;

class FavoriteGames extends Component
{
    public $favoriteGames;

    public function __construct($userId)
    {
        $profile = Profile::with('games')->where('user_id', $userId)->first();
        $this->favoriteGames = $profile ? $profile->games : collect();
    }

    public function render()
    {
        return view('components.favorite-games');
    }
}
