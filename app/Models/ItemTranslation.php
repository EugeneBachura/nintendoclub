<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTranslation extends Model
{
    protected $fillable = ['item_id', 'locale', 'name', 'description'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
