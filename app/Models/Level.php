<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents a level with rewards and requirements.
 */
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

    public function getLocalizedMessage()
    {
        $locale = app()->getLocale();
        $messages = json_decode($this->message, true);

        return $messages[$locale] ?? $messages['en'] ?? '';
    }

    public function getLocalizedDescription()
    {
        $locale = app()->getLocale();
        $descriptions = json_decode($this->description, true);

        return $descriptions[$locale] ?? $descriptions['en'] ?? '';
    }
}
