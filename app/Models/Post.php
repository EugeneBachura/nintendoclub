<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $appends = ['comments_count'];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class);
    }

    public function getCategoryNameAttribute()
    {
        $locale = app()->getLocale();

        switch ($locale) {
            case 'en':
                return $this->category->name_en ?? 'No Category';
            case 'ru':
                return $this->category->name_ru ?? 'Без категории';
            case 'pl':
                return $this->category->name_pl ?? 'Brak kategorii';
            default:
                return $this->category->name_en ?? 'No Category';
        }
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments()->where('status', 'approved')->count();
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }
}
