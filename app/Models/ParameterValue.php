<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $goods_id
 * @property int $parameter_id
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Goods|null $good
 * @property-read \App\Models\Parameter|null $parameter
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParameterValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParameterValue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParameterValue query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParameterValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParameterValue whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParameterValue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParameterValue whereParameterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParameterValue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParameterValue whereValue($value)
  */
class ParameterValue extends Model
{
    protected $table = 'parameter_values';
    protected $fillable = ['goods_id', 'parameter_id', 'value'];

    public function good(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'goods_id');
    }

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(Parameter::class);
    }
}
