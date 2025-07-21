<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



/**
 * @method static Builder|static create(array $attributes = [])
 */
class Good extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_ru', 'name_en', 'name_az',
        'title_ru', 'title_en', 'title_az',
        'keywords_ru', 'keywords_en', 'keywords_az',
        'description_ru', 'description_en', 'description_az',
        'price', 'old_price', 'count',
        'youtube_link', 'category_id',
    ];

    public function images()
    {
        return $this->hasMany(GoodImage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function parameterValues()
    {
        return $this->hasMany(ParameterValue::class);
    }
}
