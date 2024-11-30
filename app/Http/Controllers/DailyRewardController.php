<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Handles daily rewards for users.
 */
class DailyRewardController extends Controller
{
    /**
     * Collects the daily reward for a user.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function collectDailyReward(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;

        if ($profile->last_reward_collected_at && Carbon::parse($profile->last_reward_collected_at)->isToday()) {
            return redirect()->back()->withErrors(__('messages.award_collected_today'));
        }

        $profile->consecutive_days = ($profile->consecutive_days % 7) + 1;

        $rewards = match ($profile->consecutive_days) {
            1 => ['coins' => 1],
            2 => ['coins' => 2],
            3 => ['coins' => 3],
            4 => ['coins' => 4],
            5 => ['coins' => 5],
            6 => ['coins' => 6],
            7 => ['premium_points' => 1],
            default => [],
        };

        foreach ($rewards as $type => $quantity) {
            if ($type === 'coins') {
                $profile->coins += $quantity;
            } elseif ($type === 'premium_points') {
                $profile->premium_points += $quantity;
            }
        }

        $profile->last_reward_collected_at = now();
        $profile->save();

        return redirect()->back()->with('success', __('messages.daily_reward_collected'));
    }

    /**
     * Adds an item to a user's inventory.
     *
     * @param mixed $user
     * @param string $itemName
     * @param int $quantity
     * @return void
     */
    private function addItemToInventory($user, $itemName, $quantity)
    {
        $item = Item::where('name', $itemName)->firstOrFail();

        $userItem = $user->inventory()->where('item_id', $item->id)->first();

        if ($userItem) {
            $userItem->increment('quantity', $quantity);
        } else {
            $user->inventory()->create([
                'item_id' => $item->id,
                'quantity' => $quantity
            ]);
        }
    }
}
