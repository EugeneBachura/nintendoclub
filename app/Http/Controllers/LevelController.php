<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    /**
     * Displays the rewards for leveling up.
     *
     * @return \Illuminate\View\View
     */
    public function rewards()
    {
        $levels = Level::whereNotNull('experience_required')
            ->orderBy('level')
            ->get();

        $userLevel = auth()->user()->profile->level ?? null;

        return view('levels.rewards', compact('levels', 'userLevel'));
    }
}
