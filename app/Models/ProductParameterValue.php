<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $product_id
 * @property int $parameter_id
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductParameterValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductParameterValue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductParameterValue query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductParameterValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductParameterValue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductParameterValue whereParameterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductParameterValue whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductParameterValue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductParameterValue whereValue($value)
 * @mixin \Eloquent
 */
class ProductParameterValue extends Model
{
    //
}
