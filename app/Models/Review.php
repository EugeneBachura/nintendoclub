<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'rating',
    ];

    public function nickname()
    {
        return User::where('id', $this->user_id)->first()->nickname;
    }

    public function design()
    {
        return User::where('id', $this->user_id)->first()->design()->first() ?? null;
    }

    public function avatar()
    {
        return User::where('id', $this->user_id)->first()->avatar;
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }
}
