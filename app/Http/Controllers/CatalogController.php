<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class CatalogController
{
    public function index($lang = null)
    {
        $products = Product::all()->paginate(24);
        $menuCategories = Category::all();
        if ($menuCategories->isEmpty()) {
            $menuCategories = collect([(object)[
                'slug' => 'default-category',
                'name' => 'Категория по умолчанию',
            ]]);
        }
        return view('catalog.index', compact('products','menuCategories'));
    }
}
