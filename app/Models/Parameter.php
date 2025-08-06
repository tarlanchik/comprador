<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $product_type_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProductType|null $productType
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parameter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parameter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parameter query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parameter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parameter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parameter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parameter whereProductTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parameter whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Parameter extends Model
{
    protected $fillable = [
        'product_type_id',
        'name_ru',
        'name_en',
        'name_az',
    ];
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Goods::class,                // Модель товара
            'parameter_values',          // Название pivot-таблицы
            'parameter_id',              // Внешний ключ для Parameter в pivot-таблице
            'goods_id'                   // Внешний ключ для Goods в pivot-таблице
        );
    }
    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function parameterValues(): HasMany
    {
        return $this->hasMany(ParameterValue::class, 'parameter_id', 'id');
    }

}
