<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Review;
use App\Models\User;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{

    public function index()
    {
        $reviews = Review::paginate(30);
        return view('reviews.index', compact('reviews'));
    }

    public function edit($id)
    {
        $review = Review::findOrFail($id);
        return view('reviews.edit', compact('review'));
    }

    public function create()
    {
        // Вывести форму для создания новой рецензии
        return view('reviews.create');
    }

    public function store(Request $request, $gameId)
    {
        // Валидация данных
        $validatedData = $request->validate([
            'content' => 'required|string',
            'rating' => 'required|integer|min:0|max:5',
        ]);

        // Создание новой рецензии
        $review = new Review($validatedData);
        $review->user_id = auth()->user()->id;
        $review->game_id = $gameId;
        $review->language = app()->getLocale();
        $review->status = 'pending';

        $review->save();

        $game = Game::where('id', $gameId)->firstOrFail();
        $averageScore = Review::where('game_id', $gameId)->get()->avg('rating');
        $game->average_score = $averageScore;
        $game->save();

        return redirect()->route('game.show', $game->alias)->with('success', __('interfaces.review_success'));
    }

    public function update(Request $request, $gameId, $id)
    {
        // Валидация данных
        $validatedData = $request->validate([
            'content' => 'required|string',
            'rating' => 'required|integer|min:0|max:5',
        ]);

        // Найти и обновить рецензию
        $review = Review::findOrFail($id);
        $review->language = app()->getLocale();
        $review->status = 'pending';
        $review->update($validatedData);

        $game = Game::where('id', $gameId)->firstOrFail();
        $averageScore = Review::where('game_id', $gameId)->get()->avg('rating');
        $game->average_score = $averageScore;
        $game->save();

        return redirect()->route('game.show', $game->alias)->with('success', __('interfaces.review_updated'));
    }

    public function updateForAdmin(Request $request, $gameId, $id)
    {
        // Валидация данных
        $request->validate([
            'status_text' => 'nullable|string|max:255',
            'status' => 'required|in:published,closed',
            'money' => 'nullable|integer|min:50|max:100',
            'language' => 'required|in:en,pl,ru',
        ]);

        // $review = Review::findOrFail($id);
        // return redirect()->route('review.index')->with('success',  $review->money);

        // Найти и обновить рецензию
        $review = Review::findOrFail($id);
        $review->language = $request->language;
        if ($request->status == 'published') {
            if (!$review->awards) $review->awards = 0;
            $review->awards = $review->awards + $request->money;
            $wordCount = substr_count(preg_replace('/\s+/', ' ', trim($review->content)), ' ');
            $review->author->profile->addCoins($request->money);
            $review->author->profile->addExp($wordCount);
            $user = User::findOrFail($review->user_id);
            App::setLocale($request->language);
            $message = __('messages.review_awards') . ': ' . $request->money . ' ' . __('profiles.coins') . ', ' . str_word_count($wordCount) . ' ' . __('profiles.exp');
            if (app()->getLocale() == 'en') {
                $route = route('game.show', Game::findOrFail($review->game_id)->alias);
            } else {
                $route = route('game.show.locale', ['alias' => Game::findOrFail($review->game_id)->alias, 'locale' => app()->getLocale()]);
            }

            $user->notifyWithLimit(new UserNotification($message, $route));
        }
        if ($request->status == 'closed') {
            $review->status_text = $request->status_text;
            $user = User::findOrFail($review->user_id);
            $message = $request->status_text;
            if (app()->getLocale() == 'en') {
                $route = route('game.show', Game::findOrFail($review->game_id)->alias);
            } else {
                $route = route('game.show.locale', ['alias' => Game::findOrFail($review->game_id)->alias, 'locale' => app()->getLocale()]);
            }

            $user->notifyWithLimit(new UserNotification($message, $route));
        }

        $review->status = $request->status;
        $review->update();

        $game = Game::where('id', $gameId)->firstOrFail();
        $averageScore = Review::where('game_id', $gameId)->get()->avg('rating');
        $game->average_score = $averageScore;
        $game->save();

        return redirect()->route('review.index')->with('success',  __('Review updated'));
    }

    public function countWordsBySingleSpaces($text)
    {
        // Удаляем пробелы в начале и конце строки
        $trimmedText = trim($text);
        // Заменяем все последовательности пробелов и пробелы вокруг знаков препинания на одиночные пробелы
        $normalizedText = preg_replace('/\s+/', ' ', $trimmedText);
        // Считаем единичные пробелы
        $spaceCount = substr_count($normalizedText, ' ');

        // Возвращаем количество слов как количество пробелов + 1 (если строка не пуста)
        return $normalizedText === '' ? 0 : $spaceCount + 1;
    }
}
