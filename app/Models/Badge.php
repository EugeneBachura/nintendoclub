<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'icon_url', 'parent_id'];

    // Отношение к родительскому значку (если есть)
    public function parent()
    {
        return $this->belongsTo(Badge::class, 'parent_id');
    }

    // Отношение к дочерним значкам (улучшения)
    public function upgrades()
    {
        return $this->hasMany(Badge::class, 'parent_id');
    }

    // Отношение к пользователям, у которых есть этот значок
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges');
    }

    public function getTranslation($field, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();
        return $translation ? $translation->{$field} : $this->{$field};
    }
}