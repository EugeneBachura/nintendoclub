<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Review;
use Illuminate\Http\Request;
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
        $games = Game::paginate(20);
        return view('games.showAll', compact('games'));
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

    public function show($locale = null, $alias)
    {
        // Установка локализации приложения
        $locale = $locale ?? 'en';
        app()->setLocale($locale);

        // Получение новости по алиасу
        $game = Game::where('alias', $alias)->firstOrFail();

        // Загрузка перевода для заданной локализации
        // $translation = $game->translations()->where('locale', $locale)->first();

        $game->seo_description = $game->getTranslation('seo_description', $locale);
        $game->seo_keywords = $game->getTranslation('seo_keywords', $locale);
        $game->description = $game->getTranslation('description', $locale);
        $game->name = $game->getTranslation('name', $locale);


        $user = auth()->user();
        if ($user) {
            $review = Review::where('user_id', auth()->user()->id)
                ->where('game_id', $game->id)
                ->first();
        } else $review = null;

        // Получаем рецензии на языке пользователя
        $localeReviews = Review::where('game_id', $game->id)->where('status', 'published')->where('language', $locale)->orderBy('created_at', 'desc')->limit(10)->get();
        // Проверяем, нужно ли добавлять дополнительные рецензии
        if ($localeReviews->count() < 10) {
            $additionalReviews = Review::where('game_id', $game->id)
                ->where('language', '<>', $locale)
                ->orderBy('created_at', 'desc')
                ->where('status', 'published')
                ->limit(10 - $localeReviews->count())
                ->get();
            // Объединяем коллекции
            $reviews = $localeReviews->concat($additionalReviews);
        } else {
            $reviews = $localeReviews;
        }

        $averageRating = Review::where('game_id', $game->id)->where('status', 'published')->avg('rating');
        $game->average_score = $averageRating;
        $game->save();

        if ($user) {
            $user_level = $user->profile->level;
        } else {
            $user_level = 0;
        }

        return view('games.show', compact('game', 'review', 'reviews', 'user_level'));
    }

    public function showWithoutLocale($alias)
    {
        // Установка локализации приложения
        $locale = 'en';
        app()->setLocale($locale);

        // Получение новости по алиасу
        $game = Game::where('alias', $alias)->firstOrFail();

        $user = auth()->user();
        if ($user) {
            $review = Review::where('user_id', auth()->user()->id)
                ->where('game_id', $game->id)
                ->first();
        } else $review = null;


        // Получаем рецензии на языке пользователя
        $localeReviews = Review::where('game_id', $game->id)->where('status', 'published')->where('language', $locale)->orderBy('created_at', 'desc')->limit(10)->get();
        // Проверяем, нужно ли добавлять дополнительные рецензии
        if ($localeReviews->count() < 10) {
            $additionalReviews = Review::where('game_id', $game->id)
                ->where('language', '<>', $locale)
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->limit(10 - $localeReviews->count())
                ->get();
            // Объединяем коллекции
            $reviews = $localeReviews->concat($additionalReviews);
        } else {
            $reviews = $localeReviews;
        }

        $averageRating = Review::where('game_id', $game->id)->where('status', 'published')->avg('rating');
        $game->average_score = $averageRating;
        $game->save();

        if ($user) {
            $user_level = $user->profile->level;
        } else {
            $user_level = 0;
        }

        return view('games.show', compact('game', 'review', 'reviews', 'user_level'));
    }
}
