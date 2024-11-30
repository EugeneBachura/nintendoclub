<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Handles game management and display.
 */
class GameController extends Controller
{
    /**
     * Displays a paginated list of games.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $games = Game::paginate(20);
        return view('games.index', compact('games'));
    }

    /**
     * Displays all games with localization and calculated data.
     *
     * @return \Illuminate\View\View
     */
    public function showAll()
    {
        $locale = App::getLocale();
        $games = Game::paginate(20);

        foreach ($games as $game) {
            $game->localizedName = $game->getTranslation('name', $locale) ?? $game->name;
            $game->average_score = $game->average_score ?? 4.9;
            $game->score_color = $this->getScoreColor($game->average_score);
        }

        $breadcrumbs = [
            ['title' => __('Games'), 'url' => '']
        ];

        return view('games.showAll', compact('games', 'breadcrumbs'));
    }

    /**
     * Determines the score color based on the average score.
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

    /**
     * Displays the form to create a new game.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('games.create');
    }

    /**
     * Stores a new game in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
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

        $game = new Game();
        $game->fill($validatedData);

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
            $imageName = 'logo_' . $cleanedName . '.' . $extension;
            $imagePath = $request->logo_url->storeAs('games_images', $imageName, 'public');
            $game->logo_url = $imagePath;
        }

        try {
            $game->save();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error');
        }

        $game->localizations()->createMany([
            [
                'game_id' => $game->id,
                'locale' => 'ru',
                'name' => $validatedData['name'],
                'description' => $validatedData['ru_description'],
                'seo_description' => $validatedData['ru_seo_description'],
                'seo_keywords' => $validatedData['ru_seo_keywords'],
            ],
            [
                'game_id' => $game->id,
                'locale' => 'pl',
                'name' => $validatedData['name'],
                'description' => $validatedData['pl_description'],
                'seo_description' => $validatedData['pl_seo_description'],
                'seo_keywords' => $validatedData['pl_seo_keywords'],
            ]
        ]);

        return redirect()->route('game.create')->with('success', __('message.game_added'));
    }

    /**
     * Displays the form to edit a game.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $game = Game::findOrFail($id);
        return view('games.edit', compact('game'));
    }

    /**
     * Displays a game's details based on its alias.
     *
     * @param string $alias
     * @return \Illuminate\View\View
     */
    public function show($alias)
    {
        $locale = App::getLocale();
        $game = Game::where('alias', $alias)->firstOrFail();

        $game->localizedName = $game->getTranslation('name', $locale) ?? $game->name;
        $game->localizedDescription = $game->getTranslation('description', $locale) ?? $game->description;
        $game->localizedSeoDescription = $game->getTranslation('seo_description', $locale) ?? '';
        $game->localizedSeoKeywords = $game->getTranslation('keywords', $locale) ?? '';
        $game->average_score = $game->average_score ?? 4.9;
        $game->score_color = $this->getScoreColor($game->average_score);
        $releaseDate = date("d-m-Y", strtotime($game->release_date));
        $isMultiplePlatforms = strpos($game->platform, ',') !== false;

        $user = auth()->user();
        $user_level = $user ? $user->profile->level : 0;

        $review = $user ? Review::where('user_id', $user->id)->where('game_id', $game->id)->first() : null;

        $reviews = Review::where('game_id', $game->id)
            ->where('status', 'approved')
            ->with('user.design')
            ->get();

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
