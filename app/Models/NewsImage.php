<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $news_id
 * @property string $image_path
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\News|null $news
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsImage whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsImage whereNewsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsImage whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class NewsImage extends Model
{
    protected $fillable = ['news_id', 'image_path', 'sort_order'];

    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }
}
