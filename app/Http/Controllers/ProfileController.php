<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Game;
use App\Models\Profile;
use App\Models\User;
use App\Models\UserDesignSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index()
    {
        // Ищем профиль пользователя
        $user = auth()->user();
        if (is_null($user)) {
            return view('errors.no-profile');
        }

        $profile = $user->profile;
        // Проверяем, существует ли профиль
        if (is_null($profile)) {
            return view('errors.no-profile');
        }

        // Получаем опыт, необходимый для следующего уровня
        $experienceToNextLevel = $profile->experienceToNextLevel();

        // Формируем данные для передачи в представление
        $data = [
            'name' => $user->name,
            'nickname' => $user->nickname,
            'avatar' => $user->avatar,
            'experience' => $profile->experience,
            'coins' => $profile->coins,
            'level' => $profile->level,
            'birthday' => $profile->birthday,
            'premium_points' => $profile->premium_points,
            'comment_count' => $profile->comment_count,
            'profile_description' => $profile->profile_description,
            'favorite_games' => $profile->favorite_games,
            'last_active_at' => $profile->last_active_at,
            'reputation_count' => $profile->reputation_count,
            'experienceToNextLevel' => $experienceToNextLevel,
            'id' => $user->id,
            'pokemons' => $profile->pokemons,
            'badges' => $profile->badges,
            'design' => $user->design->first(),
        ];

        return view('profile.index', $data);
    }

    public function show($userId)
    {
        // Ищем профиль пользователя
        $user = User::where('id', $userId)->first();
        if (is_null($user)) {
            return view('errors.no-profile');
        }
        $profile = $user->profile;
        if (is_null($profile)) {
            return view('errors.no-profile');
        }
        $experienceToNextLevel = $profile->experienceToNextLevel($profile->level);

        // Получаем нужные данные из профиля
        $data = [
            'name' => $user->name,
            'nickname' => $user->nickname,
            'avatar' => $user->avatar,
            'experience' => $profile->experience,
            'coins' => $profile->coins,
            'level' => $profile->level,
            'birthday' => $profile->birthday,
            'premium_points' => $profile->premium_points,
            'comment_count' => $profile->comment_count,
            'profile_description' => $profile->profile_description,
            'favorite_games' => $profile->favorite_games,
            'last_active_at' => $profile->last_active_at,
            'reputation_count' => $profile->reputation_count,
            'experienceToNextLevel' => $experienceToNextLevel,
            'id' => $user->id,
            'pokemons' => $profile->pokemons,
            'badges' => $profile->badges,
            'design' => $user->design->first(),
        ];

        return view('profile.index', $data);
    }

    public function showWithLocal($locale, $userId)
    {
        // Ищем профиль пользователя
        $user = User::where('id', $userId)->first();
        if (is_null($user)) {
            return view('errors.no-profile');
        }
        $profile = $user->profile;
        if (is_null($profile)) {
            return view('errors.no-profile');
        }
        $experienceToNextLevel = $profile->experienceToNextLevel($profile->level);

        // Получаем нужные данные из профиля
        $data = [
            'name' => $user->name,
            'nickname' => $user->nickname,
            'avatar' => $user->avatar,
            'experience' => $profile->experience,
            'coins' => $profile->coins,
            'level' => $profile->level,
            'birthday' => $profile->birthday,
            'premium_points' => $profile->premium_points,
            'comment_count' => $profile->comment_count,
            'profile_description' => $profile->profile_description,
            'favorite_games' => $profile->favorite_games,
            'last_active_at' => $profile->last_active_at,
            'reputation_count' => $profile->reputation_count,
            'experienceToNextLevel' => $experienceToNextLevel,
            'id' => $user->id,
            'pokemons' => $profile->pokemons,
            'badges' => $profile->badges,
            'design' => $user->design->first(),
        ];

        return view('profile.index', $data);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $users = User::where('nickname', 'like', "%{$query}%")
            ->orWhere('name', 'like', "%{$query}%")
            ->paginate(20, ['id', 'nickname', 'name']);

        return view('profile.search', compact('users'));
    }
    /**
     * Display the user's profile form.
     */
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->profile;
        $profile_description = $profile->profile_description;
        $favoriteGames = $profile->games;
        $design = $user->design->first();
        $nickname_colors = $user->allowedNicknameColors();

        return view('profile.edit', compact('profile_description', 'favoriteGames', 'design', 'nickname_colors'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'profile_description' => 'nullable|string|max:175',
            'favorite_games' => 'nullable|array',
            'favorite_games.*' => 'exists:games,id',
            'nickname_color' => 'required|string|regex:/^#[0-9a-fA-F]{6}$/',
        ]);
        $user = Auth::user();

        // Проверяем, разрешен ли предоставленный цвет
        if (!in_array($request->input('nickname_color'), $user->allowedNicknameColors(), true)) {
            return back()->with('error', 'The provided color is not allowed.');
        }

        $profile = $user->profile;
        $profile->profile_description = $request->profile_description;
        $profile->update();

        $design = UserDesignSetting::firstOrCreate(
            ['user_id' => $user->id]
        );
        $design->nickname_color = $request->input('nickname_color');
        $design->save();


        $profile->games()->sync($request->favorite_games);

        return redirect()->route('profile.edit')->with('status', __('messages.profile_updated'));
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}