<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActivityController extends \App\Http\Controllers\Controller
{
    public function update()
    {
        try {
            $user = Auth::user();
            $profile = $user->profile;

            // Ensure that 'last_active_at' is a Carbon instance
            $lastActiveAt = Carbon::parse($profile->last_active_at);

            // Проверка на новый день
            if (!$lastActiveAt->isToday()) {
                $profile->increment('daily_visits_count');
                $profile->last_active_at = now();
                $profile->save();
            }

            $profile->update(['last_active_at' => now()]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            // Отправка JSON-ответа с информацией об ошибке
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
