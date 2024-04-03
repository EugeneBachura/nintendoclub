<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Profile extends Model
{
    use HasFactory;
    protected $fillable = [
        'experience',
        'coins',
        'level',
        'reputation_count',
        'daily_visits_count',
        'last_active_at',
        'badges'
    ];
    protected $dates = ['last_active_at'];
    protected $casts = [
        'badges' => 'array',
    ];

    public function games()
    {
        return $this->belongsToMany(Game::class, 'profile_game');
    }

    public function experienceToNextLevel($currentLevel)
    {
        // Получаем текущий уровень пользователя и следующий уровень
        $currentLevel = Level::where('level', $currentLevel)->first();
        if (!$currentLevel) return null;
        $nextLevel = Level::where('level', '>', $currentLevel->level)->first();

        if ($nextLevel) {
            // Возвращаем количество опыта, необходимое для достижения следующего уровня
            return $currentLevel->experience_required;
        }

        // Возвращаем null, если пользователь достиг максимального уровня
        return null;
    }

    public function nickname()
    {
        return User::where('id', $this->user_id)->first()->nickname;
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
        $this->save();

        $experienceToNextLevel = $this->toNextLevel($this->level);
        while ($experienceToNextLevel !== null && $this->experience >= $experienceToNextLevel) {
            $this->level++;
            // Обновляем опыт, необходимый для следующего уровня
            $experienceToNextLevel = $this->toNextLevel($this->level);
        }

        $this->save();
        Log::info("New experience value: {$this->experience}");
        return true;
    }
}
