<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Review;
use App\Models\User;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

/**
 * Handles review management actions for games.
 */
class ReviewController extends Controller
{
    /**
     * Display a list of reviews.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $reviews = Review::paginate(30);
        return view('reviews.index', compact('reviews'));
    }

    /**
     * Display the form for editing a review.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $review = Review::findOrFail($id);
        return view('reviews.edit', compact('review'));
    }

    /**
     * Display the form for creating a new review.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('reviews.create');
    }

    /**
     * Store a new review for a game.
     *
     * @param Request $request
     * @param int $gameId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $gameId)
    {
        $validatedData = $request->validate([
            'content' => 'required|string',
            'rating' => 'required|integer|min:0|max:5',
        ]);

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

    /**
     * Update a review for a game.
     *
     * @param Request $request
     * @param int $gameId
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $gameId, $id)
    {
        $validatedData = $request->validate([
            'content' => 'required|string',
            'rating' => 'required|integer|min:0|max:5',
        ]);

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

    /**
     * Update a review as an admin + rewards.
     *
     * @param Request $request
     * @param int $gameId
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateForAdmin(Request $request, $gameId, $id)
    {
        $request->validate([
            'status_text' => 'nullable|string|max:255',
            'status' => 'required|in:published,closed',
            'money' => 'nullable|integer|min:50|max:100',
            'language' => 'required|in:en,pl,ru',
        ]);

        $review = Review::findOrFail($id);
        $review->language = $request->language;

        if ($request->status == 'published') {
            if (!$review->awards) $review->awards = 0;
            $review->awards += $request->money;
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

        return redirect()->route('review.index')->with('success', __('Review updated'));
    }

    /**
     * Count words by single spaces in a text.
     *
     * @param string $text
     * @return int
     */
    public function countWordsBySingleSpaces($text)
    {
        $trimmedText = trim($text);
        $normalizedText = preg_replace('/\s+/', ' ', $trimmedText);
        $spaceCount = substr_count($normalizedText, ' ');

        return $normalizedText === '' ? 0 : $spaceCount + 1;
    }
}
