<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class News
 * 
 * Represents a news article with translations, author, reviewer, and popularity features.
 */
class News extends Model
{
    use HasFactory;

    public function translations()
    {
        return $this->hasMany(NewsTranslation::class);
    }

    /**
     * Get the translation for the given field and locale.
     *
     * @param string $field
     * @param string|null $locale
     * @return string|null
     */
    public function getTranslation($field, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();
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

    /**
     * Calculate the color based on the popularity of the news.
     *
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
     * Get a trimmed version of the news content for the specified locale.
     *
     * @param string|null $locale
     * @param int $limit
     * @return string
     */
    public function getTrimmedContent($locale = null, $limit = 100)
    {
        $locale = $locale ?? app()->getLocale();
        $content = $this->getTranslation('content', $locale);

        if (!$content) {
            return '';
        }

        $plainContent = html_entity_decode(strip_tags($content));
        return Str::limit($plainContent, $limit, '...');
    }
}
