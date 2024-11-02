<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;
    protected $fillable = ['level', 'experience_required', 'message', 'description', 'coins', 'premium_points', 'item_id', 'badge_id'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }
}