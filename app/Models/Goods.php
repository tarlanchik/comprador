<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $fillable = [
        'category_id',
        'name_ru', 'name_en', 'name_az',
        'title_ru', 'title_en', 'title_az',
        'description_ru', 'description_en', 'description_az',
        'keywords_ru', 'keywords_en', 'keywords_az',
        'price', 'old_price', 'count', 'youtube_link',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(GoodsImage::class);
    }
}
