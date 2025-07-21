<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name_ru', 'name_en', 'name_az', 'parent_id'];

    public static function getOrderedCategories()
    {
        $all = self::all();
        return self::buildTree($all);
    }

    protected static function buildTree($categories, $parentId = null, $level = 0)
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

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children');
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }
}
