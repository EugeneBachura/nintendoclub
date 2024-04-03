<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //use HasFactory;
    protected $fillable = ['name', 'description', 'price', 'currency', 'image'];
    public function translations()
    {
        return $this->hasMany(ItemTranslation::class);
    }

    public function shopItem()
    {
        return $this->hasOne(ShopItem::class);
    }

    public function getLocalizedData()
    {
        $translation = $this->translations->where('locale', app()->getLocale())->first();
        return [
            'name' => $translation ? $translation->name : $this->name,
            'description' => $translation ? $translation->description : $this->description
        ];
    }

    public function userItems()
    {
        return $this->hasMany(UserItem::class, 'item_id');
    }
}
