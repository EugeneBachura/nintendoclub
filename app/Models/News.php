<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    public function translations()
    {
        return $this->hasMany(NewsTranslation::class);
    }

    public function getTranslation($field, $locale = null)
    {
        $locale = $locale ?? app()->getLocale(); // Используйте текущую локаль приложения, если не указано иное

        // Пытаемся найти перевод для заданной локали
        $translation = $this->translations->where('locale', $locale)->first();

        // Возвращаем значение поля, если перевод существует, иначе null
        return $translation ? $translation->{$field} : null;
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function views()
    {
        return $this->hasMany(NewsView::class);
    }
}
