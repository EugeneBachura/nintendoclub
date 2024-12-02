<?php

namespace App\Http\Controllers;

use App\Models\OldUserData;
use Exception;
use Illuminate\Http\Request;

/**
 * Manages importing old user data into the system.
 */
class OldUserController extends Controller
{
    /**
     * Refresh and import old user data from a JSON file.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $jsonData = json_decode(file_get_contents('6D33u3cGXb/users.json'), true);

        OldUserData::truncate();

        $processedUsers = [];
        $errors = [];

        foreach ($jsonData as $userId => $user) {
            if (isset($user['birthday'])) {
                $year = isset($user['birthday']['year']) ? $user['birthday']['year'] : 1900;
                $year = $year == "" ? 1900 : $year;
                $month = isset($user['birthday']['month']) ? $user['birthday']['month'] + 1 : null;
                $date = isset($user['birthday']['date']) ? $user['birthday']['date'] : null;
                if (($year && $month) && $date) {
                    $birthday = "{$year}-{$month}-{$date}";
                } else $birthday = null;
            } else {
                $birthday = null;
            }
            try {
                OldUserData::create([
                    'discord_id' => $userId,
                    'money' => isset($user['money']) ? $user['money'] : null,
                    'donat' => isset($user['donat']) ? $user['donat'] : null,
                    'experience' => isset($user['exp']) ? $user['exp'] : null,
                    'level' => isset($user['lvl']) ? $user['lvl'] : 1,
                    'boost' => isset($user['boost']) ? $user['boost'] : null,
                    'sw_code' => isset($user['sw']) ? $user['sw'] : null,
                    'birthday' => $birthday,
                    'ticket_count' => isset($user['ticket']) ? $user['ticket'] : null,
                    'last_birthday_year' => isset($user['last_birthday']) ? 1900 + $user['last_birthday'] : null,
                    'message_count' => isset($user['mess_count']) ? $user['mess_count'] : null,
                    'boss_hit_count' => isset($user['boss_count']) ? $user['boss_count'] : null,
                    'word_game_score' => isset($user['word_game']) ? $user['word_game'] : null,
                    'is_banned' => isset($user['ban']) == 1 ? false : true,
                    'total_donat' => isset($user['donat_all']) ? $user['donat_all'] : null,
                    'pokemon_game_score' => isset($user['pokemons_count']) ? $user['pokemons_count'] : null
                ]);
            } catch (Exception $e) {
                $errors[] = "Ошибка при добавлении пользователя {$userId}: {$e->getMessage()}";
            }
        }

        return response()->json([
            'added_users' => $processedUsers,
            'errors' => $errors
        ]);
    }
}
