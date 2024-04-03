<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsTranslation extends Model
{
    use HasFactory;
    protected $fillable = [
        'locale', 'title', 'content', 'keywords', 'seo_description'
    ];
    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
