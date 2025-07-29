<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParameterValue extends Model
{
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
