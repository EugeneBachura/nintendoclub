<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'game_id',
        'locale',
        'name',
        'seo_description',
        'seo_keywords',
        'description'
    ];
}
