<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDesignSetting extends Model
{
    //use HasFactory;
    protected $fillable = ['user_id', 'nickname_color'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
