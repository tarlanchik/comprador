<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
 * @method static Builder|Good newModelQuery()
 * @method static Builder|Good newQuery()
 * @method static Builder|Good query()
 * @method static static create(array $attributes = [])
 * @mixin Model
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ParameterValue> $parameterValues
 * @property-read int|null $parameter_values_count
 * @method static Builder<static>|Good whereCategoryId($value)
 * @method static Builder<static>|Good whereCount($value)
 * @method static Builder<static>|Good whereCreatedAt($value)
 * @method static Builder<static>|Good whereDescriptionAz($value)
 * @method static Builder<static>|Good whereDescriptionEn($value)
 * @method static Builder<static>|Good whereDescriptionRu($value)
 * @method static Builder<static>|Good whereId($value)
 * @method static Builder<static>|Good whereKeywordsAz($value)
 * @method static Builder<static>|Good whereKeywordsEn($value)
 * @method static Builder<static>|Good whereKeywordsRu($value)
 * @method static Builder<static>|Good whereNameAz($value)
 * @method static Builder<static>|Good whereNameEn($value)
 * @method static Builder<static>|Good whereNameRu($value)
 * @method static Builder<static>|Good whereOldPrice($value)
 * @method static Builder<static>|Good wherePrice($value)
 * @method static Builder<static>|Good whereTitleAz($value)
 * @method static Builder<static>|Good whereTitleEn($value)
 * @method static Builder<static>|Good whereTitleRu($value)
 * @method static Builder<static>|Good whereUpdatedAt($value)
 * @method static Builder<static>|Good whereYoutubeLink($value)
 * @mixin \Eloquent
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

    public function images(): HasMany
    {
        return $this->hasMany(GoodImage::class);
    }

    public function category(): belongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function parameterValues(): HasMany
    {
        return $this->hasMany(ParameterValue::class);
    }
}
