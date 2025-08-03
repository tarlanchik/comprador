<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title_az
 * @property string $content_az
 * @property string $title_ru
 * @property string $content_ru
 * @property string $title_en
 * @property string $content_en
 * @property string|null $keywords_az
 * @property string|null $keywords_ru
 * @property string|null $keywords_en
 * @property string|null $description_az
 * @property string|null $description_ru
 * @property string|null $description_en
 * @property string|null $youtube_link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\NewsImage> $images
 * @property-read int|null $images_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereContentAz($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereContentEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereContentRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereDescriptionAz($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereDescriptionRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereKeywordsAz($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereKeywordsEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereKeywordsRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereTitleAz($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereTitleEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereTitleRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereYoutubeLink($value)
 * @mixin \Eloquent
 */
class News extends Model
{
    protected $fillable = [
        'title_az', 'content_az', 'title_ru', 'content_ru', 'title_en', 'content_en',
        'keywords_az', 'keywords_ru', 'keywords_en',
        'description_az', 'description_ru', 'description_en',
        'youtube_link'
    ];

    public function images(): HasMany
    {
        return $this->hasMany(NewsImage::class)->orderBy('sort_order');
    }
}

