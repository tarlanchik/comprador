<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static \Illuminate\Database\Eloquent\Model|null find($id, $columns = ['*'])
 */
class Category extends Model
{
    protected $fillable = ['name_ru', 'name_en', 'name_az', 'parent_id','product_type_id'];

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
