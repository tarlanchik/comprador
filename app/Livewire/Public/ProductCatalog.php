<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Goods;
use Livewire\Component;
use Livewire\WithPagination;

class ProductCatalog extends Component
{
    use WithPagination;

    public $categoryId = null;
    public $searchQuery = '';
    public $sortBy = 'latest';
    public $priceMin = null;
    public $priceMax = null;
    public $selectedFilters = [];
    public $categories;
    public $currentCategory;

    protected $queryString = [
        'categoryId' => ['except' => null],
        'searchQuery' => ['except' => ''],
        'sortBy' => ['except' => 'latest'],
        'priceMin' => ['except' => null],
        'priceMax' => ['except' => null],
    ];

    public function mount($categoryId = null, $search = null)
    {
        $this->categoryId = $categoryId;
        $this->searchQuery = $search ?? '';
        $this->loadCategories();

        if ($this->categoryId) {
            $this->currentCategory = Category::find($this->categoryId);
        }
    }

    public function loadCategories()
    {
        $this->categories = Category::whereNull('parent_id')
            ->with(['children.children'])
            ->get();
    }

    public function updatedSearchQuery()
    {
        $this->resetPage();
    }

    public function updatedCategoryId()
    {
        $this->resetPage();
        $this->currentCategory = $this->categoryId ? Category::find($this->categoryId) : null;
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function updatedPriceMin()
    {
        $this->resetPage();
    }

    public function updatedPriceMax()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['categoryId', 'searchQuery', 'priceMin', 'priceMax', 'selectedFilters']);
        $this->sortBy = 'latest';
        $this->resetPage();
    }

    public function addToCart($productId)
    {
        // Cart functionality will be implemented later
        $this->dispatch('add-to-cart', productId: $productId);
    }

    public function getProductsProperty()
    {
        $query = Goods::with(['images', 'category', 'parameterValues.parameter'])
            ->where('count', '>', 0);

        // Filter by category
        if ($this->categoryId) {
            $category = Category::find($this->categoryId);
            if ($category) {
                // Get all child category IDs
                $categoryIds = collect([$this->categoryId]);
                if ($category->children->count() > 0) {
                    foreach ($category->children as $child) {
                        $categoryIds->push($child->id);
                        if ($child->children->count() > 0) {
                            foreach ($child->children as $grandchild) {
                                $categoryIds->push($grandchild->id);
                            }
                        }
                    }
                }
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Search filter
        if ($this->searchQuery) {
            $locale = app()->getLocale();
            $query->where(function ($q) use ($locale) {
                $q->where("name_{$locale}", 'like', "%{$this->searchQuery}%")
                    ->orWhere("description_{$locale}", 'like', "%{$this->searchQuery}%")
                    ->orWhere("keywords_{$locale}", 'like', "%{$this->searchQuery}%");
            });
        }

        // Price filter
        if ($this->priceMin) {
            $query->where('price', '>=', $this->priceMin);
        }
        if ($this->priceMax) {
            $query->where('price', '<=', $this->priceMax);
        }

        // Sorting
        switch ($this->sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name_' . app()->getLocale(), 'asc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        return $query->paginate(12);
    }

    public function render()
    {
        return view('livewire.public.product-catalog', [
            'products' => $this->products,
        ])->layout('layouts.public');
    }
}
