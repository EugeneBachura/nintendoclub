<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'discord_id',
        'avatar',
        'nickname',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

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
        // return $this->belongsToMany(Item::class, 'user_items')
        //     ->withPivot('quantity');
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

    public function allowedNicknameColors()
    {
        $colors = ['#ffffff']; // Базовый цвет

        $hasSpecialNookItem = $this->items->contains(function ($userItem) {
            return $userItem->item->name == 'Nook Inc. Badge';
        });
        if ($hasSpecialNookItem) {
            $colors[] = '#fff300';
        }

        $hasSpecialNookItem = $this->items->contains(function ($userItem) {
            return $userItem->item->name == 'Violet Paint';
        });
        if ($hasSpecialNookItem) {
            $colors[] = '#8000FF';
        }

        if ($this->items->contains(function ($userItem) {
            return $userItem->item->name == 'Orange Paint';
        })) {
            $colors[] = '#FF8000';
        }

        if ($this->items->contains(function ($userItem) {
            return $userItem->item->name == 'Green Paint';
        })) {
            $colors[] = '#00FF00';
        }

        if ($this->items->contains(function ($userItem) {
            return $userItem->item->name == 'Blue Paint';
        })) {
            $colors[] = '#2196F3';
        }

        return $colors;
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges');
    }

    public function nickname()
    {
        return $this->nickname;
    }

    public function preferredLocale()
    {
        return $this->locale ?? app()->getLocale();
    }
}