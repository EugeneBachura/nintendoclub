<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    public function profiles()
    {
        return $this->belongsToMany(Profile::class);
    }
    public function translations()
    {
        return $this->hasMany(GameTransaction::class);
    }
    public function localizations()
    {
        return $this->hasMany(GameTransaction::class);
    }
    public function getTranslation($field, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();
        return $translation ? $translation->{$field} : null;
    }
}
