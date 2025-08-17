<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Goods;
use App\Models\News;
use App\Services\SeoService;
use Livewire\Component;

class Homepage extends Component
{
    public string $searchQuery = '';

    protected $listeners = ['add-to-cart' => '$refresh'];

    public function mount(SeoService $seoService): void
    {
        $seoService->setHomepageSeo();
    }

    public function search()
    {
        if (trim($this->searchQuery)) {
            return redirect()->route('products.search', ['q' => $this->searchQuery]);
        }
        return null;
    }

    public function addToCart($productId): void
    {
        $this->dispatch('add-to-cart', productId: $productId)->to('public.cart');
    }

    public function getFeaturedProductsProperty()
    {
        return Goods::with(['images', 'category'])
            ->where('count', '>', 0)
            ->latest()
            ->limit(8)
            ->get();
    }

    public function getLatestProductsProperty()
    {
        return Goods::with(['images', 'category'])
            ->where('count', '>', 0)
            ->latest()
            ->limit(12)
            ->get();
    }

    public function getPopularCategoriesProperty()
    {
        return Category::whereNull('parent_id')
            ->withCount('goods')
            ->orderByDesc('goods_count')
            ->limit(6)
            ->get();
    }

    public function getLatestNewsProperty()
    {
        return News::with('images')
            ->latest()
            ->limit(3)
            ->get();
    }

    public function render()
    {
        return view('livewire.public.homepage', [
            'featuredProducts' => $this->featuredProducts,
            'latestProducts'   => $this->latestProducts,
            'categories'       => $this->popularCategories, // blade $categories gözləyir
            'latestNews'       => $this->latestNews,
        ])->layout('layouts.public');
    }
}
