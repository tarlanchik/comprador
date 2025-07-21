<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParameterValue extends Model
{
    protected $fillable = ['good_id', 'parameter_id', 'value'];

    public function good()
    {
        return $this->belongsTo(Good::class);
    }

    public function parameter()
    {
        return $this->belongsTo(Parameter::class);
    }
}
