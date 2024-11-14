<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function rewards()
    {
        // Получаем все уровни и их награды, отфильтровывая пустые
        $levels = Level::whereNotNull('experience_required')
            ->orderBy('level')
            ->get();
        // Получаем уровень пользователя, если он авторизован
        $userLevel = auth()->user()->profile->level ?? null;

        return view('levels.rewards', compact('levels', 'userLevel'));
    }
}
