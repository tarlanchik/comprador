<?php

namespace App\Livewire\Public;

use App\Models\Goods;
use Livewire\Component;

class ProductDetail extends Component
{
    public Goods $product;
    public $selectedImageIndex = 0;
    public $quantity = 1;
    public $relatedProducts;

    public function mount(Goods $product)
    {
        $this->product = $product->load(['images', 'category', 'parameterValues.parameter']);
        $this->loadRelatedProducts();
    }

    public function loadRelatedProducts()
    {
        $this->relatedProducts = Goods::with(['images', 'category'])
            ->where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->where('count', '>', 0)
            ->limit(4)
            ->get();
    }

    public function selectImage($index)
    {
        if ($index >= 0 && $index < $this->product->images->count()) {
            $this->selectedImageIndex = $index;
        }
    }

    public function incrementQuantity()
    {
        if ($this->quantity < $this->product->count) {
            $this->quantity++;
        }
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        if ($this->product->count >= $this->quantity) {
            $this->dispatch('add-to-cart', [
                'productId' => $this->product->id,
                'quantity' => $this->quantity
            ]);

            session()->flash('success', 'Product added to cart successfully!');
        } else {
            session()->flash('error', 'Not enough items in stock.');
        }
    }

    public function render()
    {
        return view('livewire.public.product-detail')
            ->layout('layouts.public')
            ->title($this->product->{'title_' . app()->getLocale()} ?: $this->product->{'name_' . app()->getLocale()})
            ->section('description', $this->product->{'description_' . app()->getLocale()})
            ->section('keywords', $this->product->{'keywords_' . app()->getLocale()});
    }
}
