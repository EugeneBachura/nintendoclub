<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * User model representing application users.
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'discord_id',
        'avatar',
        'nickname',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Send a notification with a limit on the number of stored notifications.
     *
     * @param mixed $instance
     * @param int $limit
     * @return void
     */
    public function notifyWithLimit($instance, $limit = 5)
    {
        $this->notify($instance);

        $notifications = $this->notifications()->latest()->get();

        if ($notifications->count() > $limit) {
            $notifications->slice($limit)->each->delete();
        }
    }

    public function items()
    {
        return $this->hasMany(UserItem::class);
    }

    public function inventory()
    {
        return $this->hasMany(UserItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function design()
    {
        return $this->hasMany(UserDesignSetting::class);
    }

    /**
     * Get the colors allowed for the user's nickname.
     *
     * @return array<string>
     */
    public function allowedNicknameColors()
    {
        $colors = ['#ffffff']; // Base color

        $specialItems = [
            'Nook Inc. Badge' => '#fff300',
            'Violet Paint' => '#8000FF',
            'Orange Paint' => '#FF8000',
            'Green Paint' => '#00FF00',
            'Blue Paint' => '#2196F3',
        ];

        foreach ($specialItems as $itemName => $color) {
            if ($this->items->contains(fn($userItem) => $userItem->item->name == $itemName)) {
                $colors[] = $color;
            }
        }

        return $colors;
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges');
    }

    /**
     * Get the user's nickname.
     *
     * @return string
     */
    public function nickname()
    {
        return $this->nickname;
    }

    /**
     * Get the user's preferred locale.
     *
     * @return string
     */
    public function preferredLocale()
    {
        return $this->locale ?? app()->getLocale();
    }
}
