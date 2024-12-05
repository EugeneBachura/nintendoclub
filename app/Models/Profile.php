<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Notifications\UserNotification;

/**
 * Profile model managing user experience, coins, badges, and level progression.
 */
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

    /**
     * Calculate experience required for next level.
     *
     * @return int|null
     */
    public function experienceToNextLevel()
    {
        $currentLevel = $this->level;
        $currentLevelEntry = Level::where('level', $currentLevel)->first();

        if (!$currentLevelEntry) {
            return null;
        }

        $nextLevelEntry = Level::where('level', '>', $currentLevel)->orderBy('level')->first();

        return $nextLevelEntry ? $nextLevelEntry->experience_required : null;
    }

    /**
     * Get user's nickname.
     *
     * @return string
     */
    public function nickname()
    {
        return $this->user->nickname;
    }

    /**
     * Add coins to the profile.
     *
     * @param int $coins
     * @return bool
     */
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

    /**
     * Add experience to the profile and handle level ups.
     *
     * @param int $exp
     * @return bool
     */
    public function addExp($exp)
    {
        Log::info("Adding experience: current value {$this->experience}, adding {$exp}");
        if ($exp < 0) {
            return false;
        }

        $this->experience += $exp;

        while (true) {
            $experienceToNextLevel = $this->experienceToNextLevel($this->level);

            if ($experienceToNextLevel === null || $this->experience < $experienceToNextLevel) {
                break;
            }

            $this->experience -= $experienceToNextLevel;
            $this->level++;
            $this->save();

            $this->rewardForLevelUp($this->level);
        }

        $this->save();
        Log::info("New experience value: {$this->experience}, current level: {$this->level}");
        return true;
    }

    /**
     * Add premium points to the profile.
     *
     * @param int $points
     * @return bool
     */
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

    /**
     * Add an item to the user's inventory.
     *
     * @param int $itemId
     * @return bool
     */
    public function addItem($itemId)
    {
        Log::info("Adding item: {$itemId}");
        $item = Item::findOrFail($itemId);
        $user = $this->user;
        $userItem = $user->inventory()->where('item_id', $item->id)->first();
        if ($userItem) {
            if ($item->max_quantity === null || $userItem->quantity < $item->max_quantity) {
                $userItem->increment('quantity');
            } else {
                Log::info("Max items reached: {$itemId}");
            }
        } else {
            $user->inventory()->create([
                'item_id' => $item->id,
                'quantity' => 1
            ]);
            $user->profile->save();
            Log::info("Item added: {$itemId}");
        }

        return true;
    }

    /**
     * Add a badge to the user's profile.
     *
     * @param int $badgeId
     * @return bool
     */
    public function addBadge($badgeId)
    {
        $badge = Badge::findOrFail($badgeId);
        $user = $this->user;

        if (!$user->badges()->where('badges.id', $badge->id)->exists()) {
            $user->badges()->attach($badge->id);
        }

        return true;
    }

    /**
     * Handle rewards for leveling up.
     *
     * @param int $level
     */
    private function rewardForLevelUp($level)
    {
        $levelEntry = Level::where('level', $level)->firstOrFail();

        if ($levelEntry) {
            $rewardsGiven = [];

            if ($levelEntry->coins > 0) {
                $this->addCoins($levelEntry->coins);
                $rewardsGiven[] = ['type' => 'coins', 'quantity' => $levelEntry->coins];
            }

            if ($levelEntry->premium_points > 0) {
                $this->addPremiumPoints($levelEntry->premium_points);
                $rewardsGiven[] = ['type' => 'premium_points', 'quantity' => $levelEntry->premium_points];
            }

            if ($levelEntry->item_id) {
                $this->addItem($levelEntry->item_id);
                $rewardsGiven[] = ['type' => 'item', 'item_id' => $levelEntry->item_id];
            }

            if ($levelEntry->badge_id) {
                $this->addBadge($levelEntry->badge_id);
                $rewardsGiven[] = ['type' => 'badge', 'badge_id' => $levelEntry->badge_id];
            }

            if (!empty($rewardsGiven)) {
                $this->sendRewardNotification($rewardsGiven);
            }
        }
    }

    /**
     * Send notification to user about rewards.
     *
     * @param array $rewardsGiven
     */
    private function sendRewardNotification($rewardsGiven)
    {
        $user = $this->user;
        $locale = $user->preferredLocale();

        app()->setLocale($locale);

        $message = __('messages.level_up_title', ['level' => $this->level]) . "\n";

        foreach ($rewardsGiven as $reward) {
            switch ($reward['type']) {
                case 'coins':
                    $message .= __('messages.reward_coins', ['quantity' => $reward['quantity']]) . "\n";
                    break;
                case 'premium_points':
                    $message .= __('messages.reward_premium_points', ['quantity' => $reward['quantity']]) . "\n";
                    break;
                case 'item':
                    $item = Item::find($reward['item_id']);
                    $itemName = $item ? $item->getTranslation('name', $locale) : __('messages.unknown_item');
                    $message .= __('messages.reward_item', ['name' => $itemName]) . "\n";
                    break;
                case 'badge':
                    $badge = Badge::find($reward['badge_id']);
                    $badgeName = $badge ? $badge->getTranslation('name', $locale) : __('messages.unknown_badge');
                    $message .= __('messages.reward_badge', ['name' => $badgeName]) . "\n";
                    break;
            }
        }

        $url = route('inventory');

        $user->notify((new UserNotification($message, $url))->locale($locale));

        app()->setLocale(config('app.locale'));
    }
}
