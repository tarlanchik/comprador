<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodImage extends Model
{
    protected $fillable = ['good_id', 'image_path'];

    public function good()
    {
        return $this->belongsTo(Good::class);
    }
}
