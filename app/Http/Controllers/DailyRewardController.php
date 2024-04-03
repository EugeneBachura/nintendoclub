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

        $last_reward_collected_at = Carbon::parse($profile->last_reward_collected_at);

        // Проверка, можно ли собрать награду
        if ($profile->last_reward_collected_at && $last_reward_collected_at->isToday()) {
            return redirect()->back()->withErrors(__('messages.award_collected_today'));
        }

        // if ($profile->consecutive_days == 0) {
        //     $profile->consecutive_days = 1;
        // }

        $profile->consecutive_days = $profile->consecutive_days + 1;

        $rewards = [];
        switch ($profile->consecutive_days) {
            case 1:
                $profile->coins += 3;
                $rewards[] = (object) array('icon' => 'coins', 'quantity' => 3, 'item' => __('profiles.coins'));
                break;
            case 2:
                // добавить предмет "Megaball" в инвентарь пользователя
                $this->addItemToInventory($user, 'Megaball', 2);
                $rewards[] = (object) array('icon' => 'megaball', 'quantity' => 2, 'item' => __('profiles.megaball'));
                break;
            case 3:
                $profile->coins += 5;
                $rewards[] = (object) array('icon' => 'coins', 'quantity' => 5, 'item' => __('profiles.coins'));
                break;
            case 4:
                $this->addItemToInventory($user, 'Megaball', 3);
                $rewards[] = (object) array('icon' => 'megaball', 'quantity' => 3, 'item' => __('profiles.megaball'));
                break;
            case 5:
                $profile->coins += 8;
                $rewards[] = (object) array('icon' => 'coins', 'quantity' => 8, 'item' => __('profiles.coins'));
                break;
            case 6:
                $this->addItemToInventory($user, 'Megaball', 4);
                $rewards[] = (object) array('icon' => 'megaball', 'quantity' => 4, 'item' => __('profiles.megaball'));
                break;
            case 7:
                $profile->coins += 8;
                $rewards[] = (object) array('icon' => 'coins', 'quantity' => 8, 'item' => __('profiles.coins'));
                $this->addItemToInventory($user, 'Ultraball', 1);
                $rewards[] = (object) array('icon' => 'ultraball', 'quantity' => 3, 'item' => __('profiles.ultraball'));
                break;
        }

        // Обновить последнее время сбора награды и обнулить счетчик после 7 дней
        $profile->last_reward_collected_at = now();
        $profile->consecutive_days = $profile->consecutive_days % 7;
        $profile->save();

        //return redirect()->back()->with('success', __('messages.award_collected'));
        return redirect()->back()->with('rewards', $rewards);
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
