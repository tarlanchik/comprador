<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $goods_id
 * @property string $image_path
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Goods|null $goods
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsImage whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsImage whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsImage whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GoodsImage extends Model
{
    protected $fillable = ['goods_id', 'image_path', 'sort_order'];

    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'goods_id');
    }
}
