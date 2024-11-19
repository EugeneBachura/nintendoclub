<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    /* Вычисляет цвет на основе популярности новости
        * @return string
    */
    public function getPopularityColor()
    {
        $maxPopularity = 1000;
        $intensity = ($this->popularity / $maxPopularity) + 0.2;
        $red = 255;
        $green = 255 * (1 - $intensity);
        $blue = 0;

        return "rgb($red, $green, $blue)";
    }

    /**
     * Возвращает сокращённое описание новости для указанного языка.
     *
     * @param string|null $locale
     * @param int $limit
     * @return string
     */
    public function getTrimmedContent($locale = null, $limit = 100)
    {
        $locale = $locale ?? app()->getLocale(); // Используем текущую локаль, если не указана
        $content = $this->getTranslation('content', $locale); // Получаем перевод контента

        if (!$content) {
            return '';
        }

        $plainContent = html_entity_decode(strip_tags($content)); // Убираем HTML-теги и декодируем сущности
        return Str::limit($plainContent, $limit, '...'); // Ограничиваем длину текста
    }
}