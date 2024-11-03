<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Notifications\UserNotification;


class Profile extends Model
{
    use HasFactory;
    protected $fillable = [
        'experience',
        'coins',
        'level',
        'premium_points',
        'reputation_count',
        'daily_visits_count',
        'last_active_at'
    ];
    protected $casts = [
        'last_active_at' => 'datetime',
    ];

    public function games()
    {
        return $this->belongsToMany(Game::class, 'profile_game');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function badges()
    {
        return $this->user->badges();
    }

    public function experienceToNextLevel($currentLevel)
    {
        $currentLevelEntry = Level::where('level', $currentLevel)->first();
        $nextLevelEntry = Level::where('level', '>', $currentLevel)->orderBy('level', 'asc')->first();

        if ($currentLevelEntry && $nextLevelEntry) {
            // Разница между требованиями опыта следующего и текущего уровня
            return $nextLevelEntry->experience_required - $currentLevelEntry->experience_required;
        }

        return null; // Если достигнут максимальный уровень
    }

    public function nickname()
    {
        return $this->user->nickname;
    }

    public function addCoins($coins)
    {
        Log::info("Adding coins: current value {$this->coins}, adding {$coins}");
        if ($coins < 0) {
            return false;
        }
        $this->coins += $coins;
        $this->save();
        Log::info("New coins value: {$this->coins}");
        return true;
    }

    public function toNextLevel($currentLevel)
    {
        $currentLevelEntry = Level::where('level', $currentLevel)->firstOrFail();
        $nextLevelEntry = Level::where('level', '>', $currentLevel)->orderBy('level', 'asc')->first();

        if ($nextLevelEntry) {
            return $nextLevelEntry->experience_required;
        }

        return null;
    }

    public function addExp($exp)
    {
        Log::info("Adding experience: current value {$this->experience}, adding {$exp}");
        if ($exp < 0) {
            return false;
        }

        $this->experience += $exp;

        while (true) {
            $experienceToNextLevel = $this->toNextLevel($this->level);

            if ($experienceToNextLevel === null || $this->experience < $experienceToNextLevel) {
                break;
            }

            $this->experience -= $experienceToNextLevel;
            $this->level++;
            $this->save();

            // Выдаём награды за достижение нового уровня
            $this->rewardForLevelUp($this->level);
        }

        $this->save();
        Log::info("New experience value: {$this->experience}, current level: {$this->level}");
        return true;
    }

    public function addPremiumPoints($points)
    {
        Log::info("Adding premium points: current value {$this->premium_points}, adding {$points}");
        if ($points < 0) {
            return false;
        }
        $this->premium_points += $points;
        $this->save();
        Log::info("New premium points value: {$this->premium_points}");
        return true;
    }

    public function addItem($itemId)
    {
        Log::info("Adding item: {$itemId}");
        $item = Item::findOrFail($itemId);
        $user = $this->user;
        $userItem = $user->inventory()->where('item_id', $item->id)->first();
        if ($userItem) {
            // Увеличиваем количество, если не превышает max_quantity или max_quantity равно null
            if ($item->max_quantity === null || $userItem->quantity < $item->max_quantity) {
                $userItem->increment('quantity');
            } else {
                Log::info("Max items reached: {$itemId}");
            }
        } else {
            // Если товара нет в инвентаре, добавляем его
            $user->inventory()->create([
                'item_id' => $item->id,
                'quantity' => 1
            ]);
            $user->profile->save();
            Log::info("Item added: {$itemId}");
        }

        return true;
    }

    public function addBadge($badgeId)
    {
        $badge = Badge::findOrFail($badgeId);
        $user = $this->user;

        if (!$user->badges()->where('badges.id', $badge->id)->exists()) {
            $user->badges()->attach($badge->id);
        }

        return true;
    }

    private function rewardForLevelUp($level)
    {
        $levelEntry = Level::where('level', $level)->firstOrFail();

        if ($levelEntry) {
            $rewardsGiven = [];

            // Добавляем монеты, если они указаны
            if ($levelEntry->coins > 0) {
                $this->addCoins($levelEntry->coins);
                $rewardsGiven[] = ['type' => 'coins', 'quantity' => $levelEntry->coins];
            }

            // Добавляем премиум-пункты, если они указаны
            if ($levelEntry->premium_points > 0) {
                $this->addPremiumPoints($levelEntry->premium_points);
                $rewardsGiven[] = ['type' => 'premium_points', 'quantity' => $levelEntry->premium_points];
            }

            // Добавляем предмет, если он указан
            if ($levelEntry->item_id) {
                $this->addItem($levelEntry->item_id);
                $rewardsGiven[] = ['type' => 'item', 'item_id' => $levelEntry->item_id];
            }

            // Добавляем значок, если он указан
            if ($levelEntry->badge_id) {
                $this->addBadge($levelEntry->badge_id);
                $rewardsGiven[] = ['type' => 'badge', 'badge_id' => $levelEntry->badge_id];
            }

            // Отправляем уведомление пользователю о наградах
            if (!empty($rewardsGiven)) {
                $this->sendRewardNotification($rewardsGiven);
            }
        }
    }

    private function sendRewardNotification($rewardsGiven)
    {
        $user = $this->user;
        $locale = $user->preferredLocale(); // Получаем локаль пользователя

        // Составляем сообщение с учётом локали
        $message = __('messages.level_up_title', ['level' => $this->level], $locale) . "\n";

        foreach ($rewardsGiven as $reward) {
            switch ($reward['type']) {
                case 'coins':
                    $message .= __('messages.reward_coins', ['quantity' => $reward['quantity']], $locale) . "\n";
                    break;
                case 'premium_points':
                    $message .= __('messages.reward_premium_points', ['quantity' => $reward['quantity']], $locale) . "\n";
                    break;
                case 'item':
                    $item = Item::find($reward['item_id']);
                    $itemName = $item ? $item->getTranslation('name', $locale) : __('messages.unknown_item', [], $locale);
                    $message .= __('messages.reward_item', ['name' => $itemName], $locale) . "\n";
                    break;
                case 'badge':
                    $badge = Badge::find($reward['badge_id']);
                    $badgeName = $badge ? $badge->getTranslation('name', $locale) : __('messages.unknown_badge', [], $locale);
                    $message .= __('messages.reward_badge', ['name' => $badgeName], $locale) . "\n";
                    break;
            }
        }

        // URL to the inventory or profile
        $url = route('inventory.index');

        // Отправляем уведомление
        $user->notify((new UserNotification($message, $url))->locale($locale));
    }
}