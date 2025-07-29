<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductType extends Model
{
    protected $fillable = ['name'];

    public function parameters(): HasMany
    {
        return $this->hasMany(Parameter::class);
    }
}
