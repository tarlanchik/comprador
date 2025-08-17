<?php

namespace App\Http\View\Composers;

use App\Models\Category;
use Illuminate\View\View;

class CategoryComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $categories = Category::whereNull('parent_id')
            ->with(['children.children'])
            ->get();

        $view->with('categories', $categories);
    }
}
