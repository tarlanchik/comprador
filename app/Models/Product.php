<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name'];

    public function parameterValues()
    {
        return $this->hasMany(ProductParameterValue::class);
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }
}
