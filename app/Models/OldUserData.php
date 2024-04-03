<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OldUserData extends Model
{
    protected $table = 'old_user_data';

    protected $fillable = [
        'discord_id',
        'money',
        'donat',
        'experience',
        'level',
        'boost',
        'sw_code',
        'birthday',
        'ticket_count',
        'last_birthday_year',
        'message_count',
        'boss_hit_count',
        'word_game_score',
        'is_banned',
        'total_donat',
        'pokemon_game_score'
    ];
}
