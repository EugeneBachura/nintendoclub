<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyRewardController extends Controller
{
    public function collectDailyReward(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;

        // Проверяем, была ли награда собрана сегодня
        if ($profile->last_reward_collected_at && Carbon::parse($profile->last_reward_collected_at)->isToday()) {
            return redirect()->back()->withErrors(__('messages.award_collected_today'));
        }

        // Увеличиваем счетчик дней
        $profile->consecutive_days = ($profile->consecutive_days % 7) + 1;

        // Определяем награды
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

        // Применяем награды
        foreach ($rewards as $type => $quantity) {
            if ($type === 'coins') {
                $profile->coins += $quantity;
            } elseif ($type === 'premium_points') {
                $profile->premium_points += $quantity;
            }
        }

        // Сохраняем профиль
        $profile->last_reward_collected_at = now();
        $profile->save();

        return redirect()->back()->with('success', __('messages.daily_reward_collected'));
    }

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