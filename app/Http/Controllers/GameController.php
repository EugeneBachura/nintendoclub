<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::paginate(20);
        return view('games.index', compact('games'));
    }

    public function showAll()
    {
        // Получаем текущую локаль
        $locale = App::getLocale();

        // Загружаем игры с переводами и вычисляем необходимые данные
        $games = Game::paginate(20);

        foreach ($games as $game) {
            // Получаем название игры на текущем языке
            $game->localizedName = $game->getTranslation('name', $locale) ?? $game->name;

            // Вычисляем средний рейтинг
            $game->average_score = $game->average_score ?? 4.9;

            // Определяем цвет рейтинга
            $game->score_color = $this->getScoreColor($game->average_score);
        }

        // Формируем хлебные крошки
        $breadcrumbs = [
            ['title' => __('Games'), 'url' => '']
        ];

        return view('games.showAll', compact('games', 'breadcrumbs'));
    }

    /**
     * Определяет цвет рейтинга на основе среднего балла.
     *
     * @param float $averageScore
     * @return string
     */
    private function getScoreColor($averageScore)
    {
        if ($averageScore > 4) {
            return 'bg-success';
        } elseif ($averageScore > 2) {
            return 'bg-warn-hover';
        } else {
            return 'bg-accent';
        }
    }

    public function create()
    {
        return view('games.create');
    }

    public function store(Request $request)
    {
        // Валидация данных
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'nullable|image|max:255',
            'description' => 'nullable|string',
            'ru_description' => 'nullable|string',
            'pl_description' => 'nullable|string',
            'cover_image_url' => 'nullable|image|max:255',
            'release_date' => 'nullable|date',
            'platform' => 'nullable|string|max:255',
            'developer' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'alias' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:255',
            'seo_keywords' => 'nullable|string|max:255',
            'ru_seo_description' => 'nullable|string|max:255',
            'ru_seo_keywords' => 'nullable|string|max:255',
            'pl_seo_description' => 'nullable|string|max:255',
            'pl_seo_keywords' => 'nullable|string|max:255',
            'video' => 'nullable|string|max:255',
        ]);

        // Создание новой игры
        $game = new Game();
        $game->name = $request->name;
        $game->description = $request->description;
        $game->release_date = $request->release_date;
        $game->video = $request->video;
        $game->platform = $request->platform;
        $game->developer = $request->developer;
        $game->publisher = $request->publisher;
        $game->alias = $request->alias;
        $game->seo_description = $request->seo_description;
        $game->seo_keywords = $request->seo_keywords;
        if ($request->hasFile('cover_image_url')) {
            $cleanedName = preg_replace('/[^A-Za-z0-9\-_]/', '_', $request->name);
            $extension = $request->file('cover_image_url')->getClientOriginalExtension();
            $imageName = $cleanedName . '.' . $extension;
            $imagePath = $request->cover_image_url->storeAs('games_images', $imageName, 'public');
            $game->cover_image_url = $imagePath;
        }
        if ($request->hasFile('logo_url')) {
            $cleanedName = preg_replace('/[^A-Za-z0-9\-_]/', '_', $request->name);
            $extension = $request->file('logo_url')->getClientOriginalExtension();
            $imageName = 'logo' . '_' . $cleanedName . '.' . $extension;
            $imagePath = $request->logo_url->storeAs('games_images', $imageName, 'public');
            $game->logo_url = $imagePath;
        }
        try {
            $game->save();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error');
        }


        // Создание записей локализации для русского и польского языков
        $game->localizations()->create([
            'game_id' => $game->id,
            'locale' => 'ru',
            'name' => $validatedData['name'],
            'description' => $validatedData['ru_description'],
            'seo_description' => $validatedData['ru_seo_description'],
            'seo_keywords' => $validatedData['ru_seo_keywords'],
        ]);

        $game->localizations()->create([
            'game_id' => $game->id,
            'locale' => 'pl',
            'name' => $validatedData['name'],
            'description' => $validatedData['pl_description'],
            'seo_description' => $validatedData['pl_seo_description'],
            'seo_keywords' => $validatedData['pl_seo_keywords'],
        ]);

        return redirect()->route('game.create')->with('success', __('message.game_added'));
    }

    public function edit($id)
    {
        $game = Game::findOrFail($id);
        return view('games.edit', compact('game'));
    }

    public function update(Request $request, $id)
    {
        // Валидация данных и обновление игры
    }

    public function show($alias)
    {
        $locale = App::getLocale();

        // Получаем игру по алиасу
        $game = Game::where('alias', $alias)->firstOrFail();

        // Локализованные данные игры
        $game->localizedName = $game->getTranslation('name', $locale) ?? $game->name;
        $game->localizedDescription = $game->getTranslation('description', $locale) ?? $game->description;
        $game->localizedSeoDescription = $game->getTranslation('seo_description', $locale) ?? '';
        $game->localizedSeoKeywords = $game->getTranslation('keywords', $locale) ?? '';

        // Вычисляем цвет оценки
        $game->average_score = $game->average_score ?? 4.9;
        $game->score_color = $this->getScoreColor($game->average_score);

        // Форматируем дату релиза
        $releaseDate = date("d-m-Y", strtotime($game->release_date));

        // Проверяем, есть ли несколько платформ
        $isMultiplePlatforms = strpos($game->platform, ',') !== false;

        // Получаем уровень пользователя
        $user = auth()->user();
        $user_level = $user ? $user->profile->level : 0;

        // Получаем отзыв пользователя об игре, если он есть
        $review = null;
        if ($user) {
            $review = Review::where('user_id', $user->id)
                ->where('game_id', $game->id)
                ->first();
        }

        // Получаем другие отзывы об игре
        $reviews = Review::where('game_id', $game->id)
            ->where('status', 'approved')
            ->with('user.design') // Загружаем отношения пользователя и его дизайн
            ->get();

        // Формируем хлебные крошки
        $breadcrumbs = [
            ['title' => __('Games'), 'url' => localized_url('game.showAll')],
            ['title' => $game->localizedName, 'url' => ''],
        ];

        return view('games.show', compact(
            'game',
            'releaseDate',
            'isMultiplePlatforms',
            'user_level',
            'review',
            'reviews',
            'breadcrumbs'
        ));
    }
}