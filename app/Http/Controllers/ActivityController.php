<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Handles user activity updates.
 */
class ActivityController extends Controller
{
    /**
     * Updates the user's activity data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {
        try {
            $user = Auth::user();
            $profile = $user->profile;

            $lastActiveAt = Carbon::parse($profile->last_active_at);

            if (!$lastActiveAt->isToday()) {
                $profile->increment('daily_visits_count');
                $profile->last_active_at = now();
                $profile->save();
            }

            $profile->update(['last_active_at' => now()]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
