<?php

namespace App\Http\Controllers\Auth;

use App\Models\OldUserData;
use App\Models\Profile;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DiscordController extends \App\Http\Controllers\Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('discord')->redirect();
    }

    public function handleProviderCallback()
    {
        $user = Socialite::driver('discord')->stateless()->user();
        $localUser = User::where('discord_id', $user->getId())->first();

        if (!$localUser) {
            $localUserByEmail = User::where('email', $user->getEmail())->first();

            if ($localUserByEmail) {
                $localUserByEmail->update([
                    'discord_id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'avatar'   => $user->getAvatar(),
                    'nickname' => $user->user['global_name'],
                ]);
                $localUser = $localUserByEmail;
                $localUser->profile->update([
                    'last_active_at' => now(),
                ]);
            } else {
                $localUser = User::create([
                    'discord_id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'avatar'   => $user->getAvatar(),
                    'nickname' => $user->user['global_name'],
                    'password' => Hash::make(Str::random(16))
                ]);

                $oldUserData = OldUserData::where('discord_id', $user->getId())->first();
                $experience = 0;
                $coins = 0;
                $level = 1;
                $birthday = null;
                $premium_points = 0;
                $profile_description = null;
                if ($oldUserData) {
                    $experience = $oldUserData->experience ?? 0;
                    $coins = $oldUserData->money ?? 0;
                    $level = $oldUserData->level ?? 1;
                    $birthday = $oldUserData->birthday ?? null;
                    $premium_points = $oldUserData->donat ?? 0;
                    $profile_description = $oldUserData->sw_code ?? null;
                }
                $profile = new Profile([
                    'experience' => round($experience / 4),
                    'coins' => $coins,
                    'level' => round($level / 2),
                    'birthday' => $birthday,
                    'premium_points' => $premium_points,
                    'profile_description' => $profile_description,
                    'reputation_count' => 0,
                    'daily_visits_count' => 0,
                    'last_active_at' => now()
                ]);
                $localUser->profile()->save($profile);
                $localUser->assignRole('user');
            }
        } else {
            $localUser->update([
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'avatar'   => $user->getAvatar(),
                'nickname' => $user->user['global_name'],
            ]);
            $localUser->profile->update([
                'last_active_at' => now()
            ]);
        }

        Auth::login($localUser, true);
        return redirect('/profile');
    }



    public function logout()
    {
        Auth::logout();
        return redirect(route('home'));
    }
}
