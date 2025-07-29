<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Good
 *
 * @property string $name_ru
 * @property string $name_en
 * @property string $name_az
 * @property string $title_ru
 * @property string $title_en
 * @property string $title_az
 * @property string $keywords_ru
 * @property string $keywords_en
 * @property string $keywords_az
 * @property string $description_ru
 * @property string $description_en
 * @property string $description_az
 * @property float $price
 * @property float $old_price
 * @property int $count
 * @property string|null $youtube_link
 * @property int $category_id
 *
 * @method static Builder|Good newModelQuery()
 * @method static Builder|Good newQuery()
 * @method static Builder|Good query()
 * @method static static create(array $attributes = [])
 *
 * @mixin Model
 */
class Goods extends Model
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(GoodsImage::class);
       // return $this->hasMany(GoodsImage::class);
        //return $this->hasMany(GoodsImage::class, 'goods_id')->orderBy('sort_order');
    }
    public function parameterValues(): HasMany
    {
        return $this->hasMany(ParameterValue::class);
    }
}
