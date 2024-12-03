<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Badge model with relationships to parent badge, upgrades, and users.
 */
class Badge extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'icon_url', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Badge::class, 'parent_id');
    }

    public function upgrades()
    {
        return $this->hasMany(Badge::class, 'parent_id');
    }

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
