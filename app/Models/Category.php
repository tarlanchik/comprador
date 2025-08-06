<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static \App\Models\Category findOrFail(int $id, array $columns = ['*'])
 * @method static \App\Models\Category|null find(int $id, array $columns = ['*'])
 * @method static \App\Models\Category create(array $attributes = [])
 * @property int $id
 * @property string $name_ru
 * @property string $name_en
 * @property string $name_az
 * @property int|null $parent_id
 * @property int|null $product_type_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $children
 * @property-read int|null $children_count
 * @property-read Category|null $parent
 * @property-read \App\Models\ProductType|null $productType
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereNameAz($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereProductTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 */
class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name_ru', 'name_en', 'name_az', 'parent_id','product_type_id'];

    public function goods(): HasMany
    {
        return $this->hasMany(Goods::class, 'category_id'); // 'category_id' — имя внешнего ключа в таблице goods
    }
    public static function getOrderedCategories(): array
    {
        $all = self::all();
        return self::buildTree($all);
    }

    protected static function buildTree($categories, $parentId = null, $level = 0): array
    {
        $branch = [];

        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $category->level = $level;
                $branch[] = $category;

                $children = self::buildTree($categories, $category->id, $level + 1);
                foreach ($children as $child) {
                    $branch[] = $child;
                }
            }
        }
        return $branch;
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children');
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }
}
