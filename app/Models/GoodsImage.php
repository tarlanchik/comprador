<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsImage extends Model
{
    protected $fillable = ['goods_id', 'path', 'order'];

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }
}
